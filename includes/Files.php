<?php
class Flies
{
//$_FILES["attachment"]["name"][$key]

    private function normalize_files_array($files = [])
    {
        if (!is_array($files)) {
            throw new Exception("TMP files not found", 1);
            return false;
        }
        $normalized_array = [];
        foreach ($files as $index => $file) {
            if (!is_array($file['name'])) {
                $normalized_array[$index][] = $file;
                continue;
            }
            foreach ($file['name'] as $idx => $name) {
                $normalized_array[$index][$idx] = [
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                ];
            }
        }
        return $normalized_array;
    }

    public function upload_file(Intl_Connection $connection, $files, $dir, User $user = null)
    {
        $files = normalize_files_array($files);

        foreach ($files["error"] as $key => $error) {
            if (!$tmp_name = $files["tmp_name"][$key]) {
                $errormsg .= "No File found: " . $error;
                continue;
            }
            if ($error == UPLOAD_ERR_OK) {
                if (move_uploaded_file($files["name"][$tmp_name], $dir)) {
                    $file_array = pathinfo($files["tmp_name"][$key]);
                    $file_basename = $file_array['basename'];
                    $file_ext = $file_array['extension'];
                    $uid_filename = uniqid();
                    $old_file_path_name = $dir . $file_basename;
                    $new_file_path_name = $dir . $uid_filename . $file_ext;

                    if (!rename($old_file_path_name, $new_file_path_name)) {
                        $errormsg .= "Error Renaming: " . $file_name;
                        continue;
                    }
                    if (is_null($user)){
                        $connection->get_query("INSERT INTO PHOTOS (FILE_UID, EXT, FILENAME) VALUES ('{$uid_filename}','{$file_ext}','{$file_basename}')");
                    } else {
                        $connection->get_query("INSERT INTO PHOTOS (FILE_UID, EXT, FILENAME, USER_UID) VALUES ('{$uid_filename}','{$file_ext}','{$file_basename}', '{$user->get_user_var('id')}')");
                    }
                    $uploaded_array[] .= "Uploaded file '" . $file_basename . "'.<br/>\n";
                } else {
                    $errormsg .= "Could not move uploaded file '" . $files["tmp_name"][$key] . "' to '" . $file_basename . "'<br/>\n";
                }
            } else {
                $errormsg .= "Upload error. [" . $error . "] on file '" . $file_basename . "'<br/>\n";
            }
        }
        if (isset($errormsg)) {
            throw new Exception($errormsg, 1);
        } else {
            unlink($files); //deletes the temporary file
            return $uploaded_array;
        }
    }

    //TODO SELECT file path/info function
}
?>