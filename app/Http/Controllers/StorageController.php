 <?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Support\Facades\Storage;
 use Symfony\Component\HttpFoundation\Response;
 
 class StorageController extends Controller
 {
     /**
      * Serve files from storage without symlink
      * This is a workaround for shared hosting where symlinks don't work
      */
     public function serve(string $path)
     {
         // Sanitize path to prevent directory traversal
         $path = str_replace(['..', '//'], '', $path);
         
         if (!Storage::disk('public')->exists($path)) {
             abort(404);
         }
 
         $file = Storage::disk('public')->get($path);
         $mimeType = Storage::disk('public')->mimeType($path);
         $lastModified = Storage::disk('public')->lastModified($path);
 
         return response($file, Response::HTTP_OK, [
             'Content-Type' => $mimeType,
             'Content-Disposition' => 'inline',
             'Cache-Control' => 'public, max-age=31536000',
             'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
         ]);
     }
 }
