<?php
#################################################
# processJob.php
#
# Run PMAnalyzer on data
#################################################

function errorOut(&$retHash, $num, $msg) {
    $retHash["status"] = $num;
    $retHash["status_msg"] = $msg;
    echo json_encode($retHash);
    exit($retHash["status"]);
}


function getFileLinks(&$retHash, $jid) {
    $resdir = $retHash["info"]["resultsdir"];
    $retHash["imgs"] =
        array("meangrowthcurves"    => "mean_growthcurves.png",
              "mediangrowthcurves"  => "median_growthcurves.png",
              "rawgrowthcurves"     => array(),
              "growthlevels"        => "growthlevels.png");

    $files = scandir($resdir);
    # Remove parent and current directory
    $files = array_diff($files, array('..', '.'));
    foreach (glob($resdir."*.png") as $filename) {
        # Don't add previous images
        $filename = basename($filename);
        if (in_array($filename, array_values($retHash["imgs"]))) {
            continue;
        }
        $name = preg_replace('/.png/','',$filename);
        $retHash["imgs"]["rawgrowthcurves"][$name] = $filename;
    }

    # Obtain result text files
    $retHash["txt"] = array(
        0 => array("Raw Growth Curves"                => "raw_curves_".$jid.".txt"),
        1 => array("Logistic Parameters (per sample)" => "logistic_params_sample_".$jid.".txt"),
        2 => array("Logistic Curves (per sample)"     => "logistic_curves_sample_".$jid.".txt"),
        3 => array("Logistic Parameters (averaged)"   => "logistic_params_mean_".$jid.".txt"),
        4 => array("Logistic Curves (averaged)"       => "logistic_curves_mean_".$jid.".txt"),
        5 => array("All files (tar.gz)"               => "myfiles.tar.gz"));
}

#################################################
# Begin processing
#################################################
# retHash holds information sent back to primary webpage
$retHash = array("status" => 0, "status_msg" => "ok");

# Set up directory paths
$jid = $_POST["jid"];  # Job ID
$jdir = "uploads/".$jid."/";
$data = $jdir."data/";
$results = $jdir."results/";
$script = "PMAnalyzer/runPM";
$errLog = $results."errLog.txt";

if (!mkdir($results, 0776, true)) {
    errorOut($retHash, 1, "Failed to create result folder");
}

# Run analysis
exec($script." -i ".$data." -o ".$jid." -n ".$results." -m -v > ".$errLog." 2>&1");

# Tarball text files
$wd = getcwd()."/";
$tardir = $jdir.$jid."/";
$tarname = "myfiles.tar.gz";
if (!mkdir($tardir, 0776, true)) {
    errorOut($retHash, 1, "Failed to create download folder");
}

# (1) Copy results text files to download folder
# (2) Change to JobID folder
# (3) Create tar.gz file from items in download folder
# (4) Change back to home directory
exec("(cp ".$results."*".$jid.".txt ".$tardir." && cd ".$jdir.
    " && tar -czf results/".$tarname." ".$jid."/ && cd ".$wd.")");
exec("rm -r ".$tardir);

# Set return JSON object
$retHash["info"] = array("resultsdir" => $results, "jobid" => $jid);
# Fill in file links for webpage display
getFileLinks($retHash, $jid, $tarname);

echo json_encode($retHash);
?>
