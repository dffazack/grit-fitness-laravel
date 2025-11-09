<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RejectLargeUploads
{
    /**
     * Maximum allowed POST size in bytes (2MB).
     * Increase if you want a different limit.
     */
    protected $maxBytes = 2097152; // 2 * 1024 * 1024

    /**
     * List of allowed file extensions
     */
    protected $allowedExtensions = ['jpg', 'jpeg', 'png'];

    /**
     * Handle an incoming request.
     * If file upload is detected and exceeds the limit, reject with validation error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('POST')) {
            return $next($request);
        }
        // If PHP rejected the upload due to ini settings, $_FILES may contain
        // entries with error codes but $request->allFiles() could be empty.
        // Detect common PHP upload errors and return a friendly message.
        if (!empty($_FILES)) {
            foreach ($_FILES as $f) {
                // support both single and array file inputs
                if (is_array($f['error'])) {
                    foreach ($f['error'] as $err) {
                        if (in_array($err, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE])) {
                            return $this->returnError($request, 'Ukuran file terlalu besar. Maksimum 2MB.');
                        }
                    }
                } else {
                    if (in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE])) {
                        return $this->returnError($request, 'Ukuran file terlalu besar. Maksimum 2MB.');
                    }
                }
            }
        }
        // Check for POST size exceeding php.ini limits
        if ($request->server('CONTENT_LENGTH') > $this->maxBytes) {
            return $this->returnError($request, 'The file must not be greater than 2MB.');
        }

        try {
            $files = $request->allFiles();
            
            if (empty($files)) {
                return $next($request);
            }

            foreach ($files as $fieldName => $uploadedFile) {
                if (!$uploadedFile) {
                    continue;
                }

                // For multiple files in one field
                if (is_array($uploadedFile)) {
                    foreach ($uploadedFile as $file) {
                        $error = $this->validateFile($file);
                        if ($error) {
                            return $this->returnError($request, $error);
                        }
                    }
                    continue;
                }

                // Single file
                $error = $this->validateFile($uploadedFile);
                if ($error) {
                    return $this->returnError($request, $error);
                }
            }

            return $next($request);

        } catch (\Exception $e) {
            // Catch any unexpected errors and return a user-friendly message
            return $this->returnError(
                $request, 
                'The file must be a file of type: png, jpg, jpeg and not greater than 2MB.'
            );
        }
    }

    /**
     * Validate a single file
     * 
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string|null Error message if validation fails, null if passes
     */
    protected function validateFile($file)
    {
        try {
            // First check file type
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $this->allowedExtensions)) {
                return 'The file must be a file of type: png, jpg, jpeg.';
            }

            // Then check size
            if ($file->getSize() > $this->maxBytes) {
                return 'The file must not be greater than 2MB.';
            }

            return null;
        } catch (\Exception $e) {
            return 'The file must be a file of type: png, jpg, jpeg and not greater than 2MB.';
        }
    }

    /**
     * Return an error response based on the request type
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    protected function returnError($request, $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => [
                    'image' => [$message]
                ]
            ], 422);
        }

        return redirect()
            ->back()
            ->withErrors(['image' => $message])
            ->withInput();
    }
}