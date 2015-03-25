<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>PMAnalyzer</title>

    <!-- Google web fonts -->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
    <link href='https://fonts.googleapis.com/css?family=Special+Elite' rel='stylesheet' type='text/css'>

    <!-- The main CSS file -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- JavaScript Includes -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>

<body>
    <div class="wrapper">
    <h1 class="title">PMAnalyzer</h1>
    <div id="info">
        A web tool to process and analyze bacterial growth curve experimental data from Multi-phenotype Assay Plates (MAPs) or phenotype microarrays. <br/>
        Growth parameters are extracted and reported.<br/> <br/>
        To cite:
        <div style="font-size:70%; margin:0px; padding:0px">
        <i>Cuevas DA, Garza D, Sanchez SE et al. <b>Elucidating genomic gaps using phenotypic profiles.</b><br/> F1000Research 2014, 3:210 (doi: <a href="http://dx.doi.org/10.12688/f1000research.5140.1" target="_blank">10.12688/f1000research.5140.1</a>)</i>
        </div>
<!--
        Welcome to the PMAnalyzer. This is a simple web page where you can upload phenotype microarray data for analysis.<br/>
        Please note: This does not load the data into a database or otherwise store the data for you. Everything will be deleted.
        </span>
        <br/><br/>
        <h3>Uploader Notes</h3>
        <ul>
            <li>The maximum file size for uploads is <strong>1 GB</strong></li>
            <li>Uploaded files will be deleted automatically after <strong>48 hours</strong></li>
        </ul>
        <br/>
        <h4>FILE NAME FORMATS</h4>
        <p>
        When using replicates be sure to have the file name in the format:<br/>
        [Sample Name]_[Replicate Identifier]_[other text].txt<br/><br/>
        <table style="margin-left:35px;"><tr><td>Regex used:</td><td><em>Sample Name</em> [A-Za-z0-9-.]+</td></tr>
            <tr><td></td><td><em>Replicate Identifier</em> [A-Za-z0-9]+</td></tr></table>
        </p>
        <br/>
        <em>EXAMPLES</em>
        <br/>
        <p>
        <span style="color:blue">Sample names</span> &amp; <span style="color:red">Replicate ids</span> <br/>
        <b>Two samples with three replicates each</b><br/>
        <span style="color:blue">50uM-PO4</span>_<span style="color:red">A</span>.txt, <span style="color:blue">50uM-PO4</span>_<span style="color:red";>B</span>.txt, <span style="color:blue">50uM-PO4</span>_<span style="color:red">C</span>.txt, <span style="color:blue">100uM-PO4</span>_<span style="color:red">A</span>.txt, <span style="color:blue">100uM-PO4</span>_<span style="color:red">B</span>.txt, <span style="color:blue">100uM-PO4</span>_<span style="color:red">C</span>.txt<br/>
        <br/>
        <b>One sample with four replicates</b><br/>
        <span style="color:blue">ED1-July12</span>_<span style="color:red">ID1111</span>.txt, <span style="color:blue">ED1-July12</span>_<span style="color:red">ID1112</span>.txt, <span style="color:blue">ED1-July12</span>_<span style="color:red">ID1113</span>.txt, <span style="color:blue">ED1-July12</span>_<span style="color:red">ID1114</span>.txt<br/>
        </p>
-->
    </div>
    <div style="width:100%">
    <form id="upload" method="post" enctype="multipart/form-data">
            <input type="hidden" id="jid" name="jid">
            <div id="uploadheader">Please select your data</div>
            <div id="uploadbody">
                <input class="button" type="file" name="datafiles[]" multiple>
                    <br/> <br/>
                <a id="uplink">Click to upload files</a> <span id="uploadstatus"></span>
                    <br/> <br/>
                <a id="moreopts">Click here for more options</a>
                <div id="optsection">
                    <br/>
                    <span class="optitem">Parser type
                        <select name="parser">
                            <option value="1">Multi-plate Reader (v1)</option>
                            <option value="2">Well vs. Time</option>
                        </select>
                    </span>
                    <br/>
                    <span class="optitem">Generate figures
                        <span style="margin-left:20px"></span>
                        <input type="checkbox" name="figs" value="1" checked>
                    </span>
                    <br/><br/>
                    <span class="optitem">Samples and replicates
                    <div style>
                        <br/>
                        Sample: <input name="sample1" type="text" placeholder="e.g. ecoli"><br/>
                        Replicate: <input name="replicate1" type="text" placeholder="e.g. rep1">
                    </div>
                    </span>
                </div>
                    <br/> <br/>
                <!-- <div id="submitdiv"> <input class="button" id="submit" type="submit" value="Submit job"> </div>  -->
                <a id="submit" class="not-active">Click to submit job</a> <span id="status"></span>
            </div>
    </form>
    </div>


    <div id="results"></div>
    <div class="push"></div>
    </div> <!-- End wrapper -->

<!--
    <footer>
        <h2><a href="https://edwards.sdsu.edu/research/" title="Edwards Bioinformatics Lab Site" target="_blank"><i>Visit Rob Edwards Lab</i></a></h2>
        <div id="footersection">
            <a id="downloadsection" href="https://github.com/dacuevas/PMAnalyzer" title="Download from GitHub" target="_blank">PMAnalyzer @ GitHub</a>
        </div>
    </footer>
-->

    <footer>
        <span id="edwardslab">
            <a class="downloadsection" href="https://edwards.sdsu.edu/research/" title="Edwards Bioinformatics Lab Site" target="_blank">Visit Rob Edwards Lab</a>
        </span>
        <span id="footersection">
            <a class="downloadsection" href="https://github.com/dacuevas/PMAnalyzer" title="Download from GitHub" target="_blank">PMAnalyzer @ GitHub</a>
        </span>
    </footer>



    <script src="assets/js/script.js" type="text/javascript"></script>

</body>
</html>
