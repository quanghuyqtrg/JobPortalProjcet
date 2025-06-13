<?php







namespace App\Http\Controllers;







use Illuminate\Http\Request;



use App\Models\User;



use App\Models\Resume;



use App\Models\CvFile;



use App\Models\Candidate;



use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\Log;



use Illuminate\Support\Facades\DB;



use App\Models\CandidateAnalyze;











class CandidateController extends Controller
{



    protected $cvParserService;















    public function showProfileForm()
    {



        $user = Auth::user();







        // Check if user is a candidate



        if ($user->account_type !== 'candidate') {



            return redirect('/dashboard')->with('error', 'Only candidates can access this page.');



        }







        // Get user's resume



        $resume = Resume::where('user_id', $user->id)->with('cvFile')->first();







        return view('candidate.profile', compact('user', 'resume'));



    }







    public function updateProfile(Request $request)
    {



        $user = Auth::user();







        // Check if user is a candidate



        if ($user->account_type !== 'candidate') {



            return redirect('/dashboard')->with('error', 'Only candidates can access this feature.');



        }







        $validated = $request->validate([



            'full_name' => 'required|string|max:255',



            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),



            'phone' => 'nullable|string|max:15',



            'skills' => 'nullable|string',



            'experience' => 'nullable|string',



            'education' => 'nullable|string',



            'resume' => 'required|file|mimes:pdf,docx|max:10240',



        ]);







        // Update user data with validated data



        User::where('id', $user->id)->update([



            'full_name' => $validated['full_name'],



            'email' => $validated['email'],



            'phone' => $validated['phone'],



        ]);







        if ($request->hasFile('resume')) {



            try {



                // Create directory if it doesn't exist



                if (!Storage::disk('public')->exists('resumes')) {



                    Storage::disk('public')->makeDirectory('resumes');



                }







                $path = $request->file('resume')->store('resumes', 'public');



                $fullPath = Storage::disk('public')->path($path);







                // LÃ†Â°u thÃƒÂ´ng tin CV vÃƒÂ o database



                $cvFile = CvFile::create([



                    'user_id' => Auth::id(),



                    'file_name' => $request->file('resume')->getClientOriginalName(),



                    'file_url' => $path,



                    'file_type' => $request->file('resume')->getClientOriginalExtension(),



                ]);







                // CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t resume



                $resume = Resume::updateOrCreate(



                    ['user_id' => Auth::id()],



                    [



                        'title' => 'HÃ¡Â»â€œ sÃ†Â¡ cÃ¡Â»Â§a ' . Auth::user()->full_name,



                        'cv_file_id' => $cvFile->id,



                        'parsing_status' => 'pending',



                    ]



                );



                $webhookUrl = "https://n8n.wepro.io.vn/webhook/huyupload_cv";



                $filePath = Storage::disk('public')->path($resume->cvFile->file_url);



                $ch = curl_init();







                curl_setopt($ch, CURLOPT_URL, $webhookUrl);



                curl_setopt($ch, CURLOPT_POST, true);



                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Equivalent to withoutVerifying()



                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);







                // Prepare the file for upload



                //$file = file_get_contents($filePath);







                $file = new \CurlFile($filePath, mime_content_type($filePath), basename($filePath));



                // Prepare POST data



                $postData = [



                    'job_id_apply' => 1,



                    'cv_id' => $cvFile->id,


                    'text' => "cÃƒÂ´ng nghÃ¡Â»â€¡ thÃƒÂ´ng tin",



                    'created_at' => $resume->cvFile->created_at->toDateTimeString(),



                    'cv_file' => $file



                ];







                // Set POST fields



                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);







                // Execute request



                $response = curl_exec($ch);







                // Check for errors



                if (curl_errno($ch)) {



                    $error = curl_error($ch);



                    // Handle error as needed



                }







                // Close cURL session



                curl_close($ch);



                /* $response = Http::withoutVerifying()->attach(



                     'cv_file',



                     file_get_contents($filePath),



                     basename($filePath)



                 )->post($webhookUrl, [



                     'job_id_apply' => $this->jobId,



                     'cv_id' => $this->resumeId,



                     'text' => "cÃƒÂ´ng nghÃ¡Â»â€¡ thÃƒÂ´ng tin",                  



                     'created_at' => $resume->cvFile->created_at->toDateTimeString(),



                 ]);



                  */



                // GÃ¡Â»Â­i CV Ã„â€˜Ã¡ÂºÂ¿n n8n Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch



                //dispatch(new N8NQueueJob(null, $resume->id));







                return redirect('/candidate/profile')->with('success', 'HÃ¡Â»â€œ sÃ†Â¡ Ã„â€˜ÃƒÂ£ Ã„â€˜Ã†Â°Ã¡Â»Â£c cÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t!');



            } catch (\Exception $e) {



                return redirect('/candidate/profile')->with('error', 'CÃƒÂ³ lÃ¡Â»â€”i khi tÃ¡ÂºÂ£i lÃƒÂªn hÃ¡Â»â€œ sÃ†Â¡: ' . $e->getMessage());



            }



        }







        return redirect('/candidate/profile')->with('success', 'ThÃƒÂ´ng tin cÃƒÂ¡ nhÃƒÂ¢n Ã„â€˜ÃƒÂ£ Ã„â€˜Ã†Â°Ã¡Â»Â£c cÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t!');



    }






    /**



     * HiÃ¡Â»Æ’n thÃ¡Â»â€¹ kÃ¡ÂºÂ¿t quÃ¡ÂºÂ£ phÃƒÂ¢n tÃƒÂ­ch CV



     *



     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse



     */



    public function showResumeAnalysis()
    {



        $user = Auth::user();



        $resume = Resume::where('user_id', $user->id)->first();







        if (!$resume) {



            return redirect()->route('candidate.profile')->with('error', 'BÃ¡ÂºÂ¡n chÃ†Â°a cÃƒÂ³ hÃ¡Â»â€œ sÃ†Â¡ CV nÃƒÂ o.');



        }







        return view('candidate.resume-analysis', compact('resume'));



    }







    /**



     * GÃ¡Â»Â­i CV Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch



     *



     * @param Request $request



     * @return \Illuminate\Http\RedirectResponse



     */



    public function parseCV(Request $request)
    {



        $resumeId = $request->input('resume_id');



        $resume = Resume::with('cvFile')->find($resumeId);







        if (!$resume || !$resume->cvFile) {



            return redirect()->route('candidate.profile')->with('error', 'KhÃƒÂ´ng tÃƒÂ¬m thÃ¡ÂºÂ¥y CV.');



        }







        // CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t trÃ¡ÂºÂ¡ng thÃƒÂ¡i



        $resume->update([



            'parsing_status' => 'pending'



        ]);







        // GÃ¡Â»Â­i CV Ã„â€˜Ã¡ÂºÂ¿n n8n Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch









        return redirect()->route('candidate.profile')->with('success', 'CV cÃ¡Â»Â§a bÃ¡ÂºÂ¡n Ã„â€˜ÃƒÂ£ Ã„â€˜Ã†Â°Ã¡Â»Â£c gÃ¡Â»Â­i Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch. Vui lÃƒÂ²ng Ã„â€˜Ã¡Â»Â£i trong giÃƒÂ¢y lÃƒÂ¡t.');



    }







    public function startParsingCV($resumeId)
    {



        $resume = Resume::with('cvFile')->find($resumeId);







        if (!$resume || !$resume->cvFile) {



            return redirect()->route('candidate.profile')->with('error', 'KhÃƒÂ´ng tÃƒÂ¬m thÃ¡ÂºÂ¥y CV.');



        }







        // CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t trÃ¡ÂºÂ¡ng thÃƒÂ¡i



        $resume->update([



            'parsing_status' => 'pending'



        ]);







        // GÃ¡Â»Â­i CV Ã„â€˜Ã¡ÂºÂ¿n n8n Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch









        return redirect()->route('candidate.profile')->with('success', 'CV cÃ¡Â»Â§a bÃ¡ÂºÂ¡n Ã„â€˜ÃƒÂ£ Ã„â€˜Ã†Â°Ã¡Â»Â£c gÃ¡Â»Â­i Ã„â€˜Ã¡Â»Æ’ phÃƒÂ¢n tÃƒÂ­ch. Vui lÃƒÂ²ng Ã„â€˜Ã¡Â»Â£i trong giÃƒÂ¢y lÃƒÂ¡t.');



    }







    /**



     * Convert JSON string to array



     * 



     * @param string $jsonString



     * @return array



     */



    private function convertJson($jsonString)
    {



        if (empty($jsonString)) {



            return [];



        }







        try {



            return json_decode($jsonString, true) ?? [];



        } catch (\Exception $e) {



            return [];



        }



    }







    /**



     * API Ã„â€˜Ã¡Â»Æ’ nhÃ¡ÂºÂ­n dÃ¡Â»Â¯ liÃ¡Â»â€¡u phÃƒÂ¢n tÃƒÂ­ch CV tÃ¡Â»Â« n8n workflow



     * 



     * @param Request $request



     * @return \Illuminate\Http\JsonResponse



     */



    public function N8NScreenResult(Request $request)
    {



        try {



            Log::info('N8NScreenResult: Request received', [



                'input_data' => $request->all(),



                'ip' => $request->ip()

            ]);







            $CVid = $request->input("cv_uid");



            Log::info('CV ID received: ' . $CVid);







            $jobId = $request->input("job_uid");



            Log::info('Job ID received: ' . $jobId);







            $parserData = $request->input("data_parser");



            Log::info('Parser data received (type): ' . gettype($parserData));







            if (empty($parserData)) {



                Log::error('Parser data is empty');



                return response()->json(['status' => 'error', 'message' => 'Parser data is empty'], 400);



            }







            $parserData = json_decode($parserData, true);



            if (json_last_error() !== JSON_ERROR_NONE) {



                Log::error('JSON parsing error: ' . json_last_error_msg());



                return response()->json(['status' => 'error', 'message' => 'Failed to parse JSON: ' . json_last_error_msg()], 400);



            }







            Log::info('Parser data after JSON decode (structure): ', [



                'has_basic_info' => isset($parserData['Basic_Information']),



                'has_experience' => isset($parserData['Company_Experience']),



                'has_skills' => isset($parserData['Skills']),



                'has_education' => isset($parserData['Education']),



                'has_usp' => isset($parserData['Unique_Selling_Proposition'])



            ]);







            try {



                $candidate = new CandidateAnalyze();



                $candidate->cv_id = $CVid;



                $candidate->fullname = $parserData['Basic_Information']['full_name'] ?? "";



                $candidate->phone_number = $parserData['Basic_Information']['contact_details']['phone_number'] ?? "";



                $candidate->email = $parserData['Basic_Information']['contact_details']['email'] ?? "";



                $candidate->other_contacts = json_encode($parserData['Basic_Information']['contact_details']['other_contacts'] ?? array()) ?? "";



                $candidate->experience = json_encode($parserData['Company_Experience'] ?? array()) ?? "";



                $candidate->projects = json_encode($parserData['Projects'] ?? array());



                $candidate->total_years_of_experience = $parserData['Total_Years_of_Experience'] ?? 0;



                $candidate->skills = json_encode($parserData["Skills"] ?? array()) ?? "";



                $candidate->education = json_encode($parserData["Education"] ?? array()) ?? "";



                $candidate->proposition = $parserData["Unique_Selling_Proposition"] ?? "";







                Log::info('Candidate model created with data', [



                    'cv_id' => $candidate->cv_id,



                    'fullname' => $candidate->fullname,



                    'phone' => $candidate->phone_number,



                    'email' => $candidate->email



                ]);







                $candidate->save();



                Log::info('Candidate saved to database with ID: ' . $candidate->id);



            } catch (\Exception $e) {



                Log::error('Error creating or saving candidate: ' . $e->getMessage(), [



                    'exception' => $e,



                    'trace' => $e->getTraceAsString()



                ]);



                return response()->json(['status' => 'error', 'message' => 'Error saving candidate: ' . $e->getMessage()], 500);



            }







            try {



                $analyzeData = $this->convertJson($request->input('result'));



                Log::info('Analyze data after conversion: ', [



                    'analyze_data' => $analyzeData



                ]);







                $score = $analyzeData['total_score'] ?? 0;



                $reason = $analyzeData['reason'] ?? "";



                $position_match = $analyzeData['position_match'] ?? 0;



                $experience = $analyzeData['experience'] ?? 0;



                $skills = $analyzeData['skills'] ?? 0;



                $education = $analyzeData['education'] ?? 0;



                $location_match = $analyzeData['location'] ?? 0;



                $industry_fit = $analyzeData['industry_fit'] ?? 0;







                $status = 1;







                try {



                    Log::info('Updating cv_file_job_recuirtment table', [



                        'cv_id' => $CVid,



                        'job_id' => $jobId,



                        'score' => $score



                    ]);







                    DB::update(



                        "UPDATE `cv_file_job_recuirtment`



                         SET `score` = ?, `reason` = ?, `status` = ?, `position_match` = ?,  



                             `experience` = ?, `skills` = ?, `education` = ?, `location_match` = ?, `industry_fit` = ?



                         WHERE `cv_file_id` = ? AND `job_recuirtment_id` = ?",



                        [$score, $reason, $status, $position_match, $experience, $skills, $education, $location_match, $industry_fit, $CVid, $jobId]



                    );







                    Log::info('Database update completed successfully');



                    return response()->json(['status' => 'success', 'message' => 'Data processed successfully']);



                } catch (\Exception $e) {



                    Log::error('Database update error: ' . $e->getMessage(), [



                        'sql_error' => $e->getMessage(),



                        'trace' => $e->getTraceAsString()



                    ]);



                    return response()->json(['status' => 'error', 'message' => 'Database update error: ' . $e->getMessage()], 500);



                }



            } catch (\Exception $e) {



                Log::error('Error processing analysis data: ' . $e->getMessage(), [



                    'exception' => $e,



                    'trace' => $e->getTraceAsString()



                ]);



                return response()->json(['status' => 'error', 'message' => 'Error processing analysis data: ' . $e->getMessage()], 500);



            }



        } catch (\Exception $e) {



            Log::error('Critical error in N8NScreenResult: ' . $e->getMessage(), [



                'exception' => $e,



                'trace' => $e->getTraceAsString(),



                'request_data' => $request->all()



            ]);



            return response()->json(['status' => 'error', 'message' => 'Critical error: ' . $e->getMessage()], 500);



        }



    }







    public function showCvAnalysis()
    {

        $user = Auth::user();

        // Láº¥y báº£n ghi candidate_analyze má»›i nháº¥t cá»§a user dá»±a trÃªn cv_id liÃªn káº¿t vá»›i user_id

        $cvFile = CvFile::where('user_id', $user->id)->orderByDesc('id')->first();

        $candidateAnalyze = null;

        if ($cvFile) {

            $candidateAnalyze = CandidateAnalyze::where('cv_id', $cvFile->id)->orderByDesc('id')->first();

        }

        return view('candidate.cv-analysis', compact('candidateAnalyze'));

    }



}