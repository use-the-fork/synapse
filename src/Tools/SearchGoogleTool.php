<?php

  namespace UseTheFork\Synapse\Tools;

  use UseTheFork\Synapse\Attributes\Description;
  use Illuminate\Support\Facades\Storage;

  #[Description('Search Google using a query.')]
  final class SearchGoogleTool
  {
    public function handle(
      #[Description('the search query to execute')]
      string $query,
    ): string {


      dd(
        $query
      );

      // Make sure it's a relative path
      if (str_contains($file_path, Storage::path(DIRECTORY_SEPARATOR))) {
        $file_path = str_replace(Storage::path(DIRECTORY_SEPARATOR), '', $file_path);
      }

      if (Storage::exists($file_path)) {
        render(view('tool', [
          'name' => 'ReadFile',
          'output' => $file_path,
        ]));

        return Storage::get($file_path);
      }

      $output = 'The file does not exist in the path: '.$file_path;
      render(view('tool', [
        'name' => 'ReadFile',
        'output' => $output,
      ]));

      return $output;
    }
  }
