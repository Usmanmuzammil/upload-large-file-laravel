<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function create()
    {
        return view('media');
    }

    public function uploadMedia(Request $request)
    {
        $file = $request->file('file');
        $chunkNumber = $request->input('resumableChunkNumber');
        $totalChunks = $request->input('resumableTotalChunks');
        $fileName = $request->input('resumableFilename');

        // Define the path to the public/uploads directory
        $uploadDirectory = public_path('uploads');
        
        // Ensure the directory exists, create if not
        if (!File::exists($uploadDirectory)) {
            File::makeDirectory($uploadDirectory, 0755, true);
        }

        // Define the temporary chunk file path
        $chunkFilePath = $uploadDirectory . '/' . $fileName . '.part' . $chunkNumber;

        // Move the uploaded chunk to the public/uploads directory
        $file->move($uploadDirectory, $fileName . '.part' . $chunkNumber);

        // If this is the last chunk, merge all the parts
        if ($chunkNumber == $totalChunks) {
            $this->mergeChunks($fileName, $totalChunks, $uploadDirectory);
        }

        return response()->json(['message' => 'Chunk uploaded successfully']);
    }

    private function mergeChunks($fileName, $totalChunks, $uploadDirectory)
    {
        // Define the final file path where the file will be saved after merging
        $finalFilePath = $uploadDirectory . '/' . $fileName;

        // Open the final file in write-binary mode
        $output = fopen($finalFilePath, 'wb');

        // Loop through each chunk, merge them and remove the chunk file after processing
        for ($i = 1; $i <= $totalChunks; $i++) {
            $chunkPath = $uploadDirectory . '/' . $fileName . '.part' . $i;

            // Open the chunk file in read-binary mode
            $chunkFile = fopen($chunkPath, 'rb');

            // Copy the chunk content to the final file
            stream_copy_to_stream($chunkFile, $output);

            // Close the chunk file and remove it
            fclose($chunkFile);
            unlink($chunkPath);
        }

        // Close the final output file
        fclose($output);
    }
}


?>