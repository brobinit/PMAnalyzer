<?php
#################################################
# upload.php
#
# Preprocess files to be sure they are safe.
#################################################


function errorOut(&$retHash, $num, $msg) {
    $retHash["status"] = $num;
    $retHash["status_msg"] = $msg;
    echo json_encode($retHash);
    exit($retHash["status"]);
}


function check_file_is_empty(&$retHash, $filename) {
    if (filesize($filename) == 0) {
        errorOut($retHash, 1, "Empty file.");
    }

    return true;

}


function check_file_name(&$retHash, $filename) {
    if (preg_match('/\.(php|exe|cgi|pl|sh|py|jsp)/i', $filename)) {
        errorOut($retHash, 1, "Scripts not allowed. [$filename]");
    }
    elseif (preg_match('/(\+|\$|\@|\#|\^|\&|\*|\!)/', $filename)) {
        errorOut($retHash, 1, "[+ \$ @ # ^ & * !] not allowed in filenames. [$filename]");
    }
    elseif (mb_strlen($filename, "UTF-8") > 225 ) {
        errorOut($retHash, 1, "Filename cannot be bigger than 250 characters.");
    }

    return true;
}

################################################
# Begin processing
################################################
# retHash holds information sent back to primary webpage
$retHash = array("status" => 0, "status_msg" => "ok");

# Begin checking files
foreach ($_FILES["datafiles"]["error"] as $idx => $error) {
    # First check if upload was okay
    if ($error != UPLOAD_ERR_OK) {
        errorOut($retHash, 1, "Bad upload for ".$key);
    }
    $filepath = $_FILES["datafiles"]["tmp_name"][$idx];
    $filename = $_FILES["datafiles"]["name"][$idx];
    # Check file is empty
    check_file_is_empty($retHash, $filepath);

    # Check for php, exe, or any other characters
    check_file_name($retHash, $filename);
}

# Move files into their appropriate directory
# Use the job id to name the directory
$jid = $_POST["jid"];  # Job ID
$uploads_dir = "uploads/".$jid."/data/";

# Create the directory
if (!mkdir("uploads/".$jid, 0775, true)) {
    errorOut($retHash, 1, "Failed to create folders");
}
if (!mkdir($uploads_dir, 0775, true)) {
    errorOut($retHash, 1, "Failed to create folders");
}
foreach ($_FILES["datafiles"]["tmp_name"] as $idx => $filename) {
    $name = $_FILES["datafiles"]["name"][$idx];
    move_uploaded_file($filename, "$uploads_dir/$name");
}

echo json_encode($retHash);
?>
