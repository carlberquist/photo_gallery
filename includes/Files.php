<?php
class Files
{
    //TODO add interface for files
    //TODO add method get photos by user id, method get all photos.
    //TODO create new class that uses the $_FILES array directly
    //TODO add update function

    public $table_name = 'photographs';
    private $folder = 'images';
    private $root_folder;
    private $connection;
    private $user;
    private $pagination;
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;
    public $user_id;
    public $file_path;
    public $files;

    public function __construct(Int_Connection $connection, Int_User $user = null, Pagination $pagination = null, $table_name = null, $folder = null)
    {
        $this->connection = $connection;
        $this->pagination = $pagination;
        $this->user = $user;
        if (!is_null($table_name)) {
            $this->table_name = $table_name;
        }
        if (!is_null($folder)) {
            $this->folder = $folder;
        }
        $this->root_folder = PUBLIC_FOLDER . $this->folder . DS;
    }

    private function normalize_files_array($files = [])
    {
        if (!is_array($files)) {
            throw new Exception("TMP files not found", 1);
            return false;
        }
        $normalized_array = [];
        foreach ($files as $index => $file) {
            if (!is_array($file)) {
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
//files needs whole $_FILE array
//TODO create error array with messages for each error 1,2,3 etc
    public function upload_file($files)
    {
        $move_success = false;
        $errormsg = "";
        if (!isset($files)) {
            throw new Exception("No files selected for upload", 1);
            return false;
        }
        if (is_null($this->user)) {
            throw new Exception("User not logged in", 1);
            return false;
        }
        $files = $this->normalize_files_array($files);
        $uploaded_array = array();
        foreach ($files["error"] as $key => $error) {
            if (!$tmp_name = $files["tmp_name"][$key]) {
                $errormsg .= "No File found: " . $error;
                continue;
            }
            if ($error == UPLOAD_ERR_OK) {
                $uid_filename = uniqid();
                $file_array = pathinfo($files["name"][$key]);
                $file_basename = $file_array['basename'];
                $file_ext = $file_array['extension'];
                if (move_uploaded_file($tmp_name, $this->root_folder . $uid_filename . "." . $file_ext)) {
                    $move_success = true;
                } else {
                    $errormsg .= "Could not move uploaded file '" . $file_basename . "'<br/>\n";
                    continue;
                }
                //TODO add size? and caption
                $stmnt = $this->connection->get_query("INSERT INTO " . $this->table_name . " (id, filename, type, size, caption, user_id) VALUES ('{$uid_filename}','{$file_basename}','{$file_ext}',null, null,'" . $this->user->get_user_var('id') . "')");
                if ($stmnt->affected_rows() == 1 && $move_success === true) {
                    $uploaded_array[] = $file_basename;
                } else {
                    $this->delete_file($uid_filename, $file_ext, $file_basename);
                    $errormsg .= "Upload failed on file {$file_basename}<br/>\n";
                }
            } else {
                $errormsg .= "Upload error. [" . $error . "] on file '" . $file_basename . "'<br/>\n";
            }
        }
        if (!empty($errormsg)) {
            throw new Exception($errormsg, 1);
            return false;
        } else {
            return $uploaded_array;
        }
    }
    //TODO check file basename and type extension in database
    public function set_file_by_id($id)
    {
        $this->connection->get_query("SELECT * FROM " . $this->table_name . " WHERE id = '{$id}'", $this);
        if (file_exists($file_path = $this->root_folder . $this->id . "." . $this->type)) {
            $this->file_path = $file_path;
            return true;
        } else {
            throw new Exception("{$this->filename} not found", 1);
            return false;
        }
    }
    //set user first before calling
    public function get_file_by_user_id()
    {
        $this->pagination->set_sql("SELECT * FROM " . $this->table_name . " WHERE user_id = '" . $this->user->id . "'");
        $this->files = $this->process_selected_files($this->connection->get_query($this->pagination->get_offset($this->pagination->get_offset(), true)));
    }
    public function get_all_files()
    {
        $this->pagination->set_sql("SELECT * FROM " . $this->table_name . "");
        $this->files = $this->process_selected_files($this->connection->get_query($this->pagination->get_offset(), true));
    }
    private function process_selected_files($stmnt)
    {
        foreach ($stmnt as $row) {
            $file_path = $row->id . "." . $row->type;
            if (!file_exists($this->root_folder . $file_path)) {
                throw new Exception("file {$row->filename} not found <br /></n>");
                return false;
            }
            $row->file_path = $this->folder . "/" . $file_path;
        }
        return $stmnt;
    }
    public function delete_file($id, $ext, $filename)
    {
        $file_path = $this->root_folder . $id . "." . $ext;
        if ($this->connection->get_query("DELETE FROM " . $this->table_name . " WHERE id = '" . $id . "'") && file_exists($file_path)) {
            unlink($file_path);
            return "Photo {$filename} successfully deleted";
        } else {
            return "Delete photo {$filename} failed";
        }
    }
    public function get_file_var($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        } else {
            throw new Exception("{$var} not found in " . get_class($this), 1);
            return false;
        }
    }
}
?>