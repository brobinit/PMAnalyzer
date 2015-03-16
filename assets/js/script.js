/*****************************************************************
 * JQuery assignments
*****************************************************************/

// Choose files
$(":file").change(function(){ chooseFiles(); });

// When files are chosen, attempt to upload
$("#uplink").click(function(e){ uploadFile(e); });

// Submit form link
$("#submit").click(function(e){ processJob(e); });

// Options section
$("#moreopts").click(function(e){ $("#optsection").slideToggle("slow"); });



/*****************************************************************
 * Callback functions
 *****************************************************************/

/***********************************
 * Reset fields after choosing files
 ***********************************/
function chooseFiles() {
    $("#uplink").removeClass("not-active");
    $("#uploadstatus").html("");
    $("#status").html("");
}

/***********************************
 * File upload function
 ***********************************/
function uploadFile(e) {
    e.preventDefault();
    var myform = $("#upload")[0];
    var jid = new Date().valueOf();
    jid += Math.floor((Math.random() * 10) + 1);
    $("#jid").val(jid);

    $.ajax({
        url: "upload.php",
        type: "POST",
        data: new FormData(myform),
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(data){
        data = JSON.parse(data);
        clearInterval($analysisInt);
        // Check the upload status
        if (data["status"] == 0) {
            //$("#upbutton").prop("disabled", true)
            $("#uploadstatus").html("OK");
            // Show submit button
            $("#submit").removeClass("not-active");
            $("#uplink").addClass("not-active");
        }
        else{
            $("#uploadstatus").html(data["status_msg"]);
        }
    })
    .fail(function(data){
        data = JSON.parse(data);
        clearInterval($analysisInt);
        if (data["status"] != 0){
            $("#uploadstatus").html(data["status_msg"]);
        }
    });
    $("#uploadstatus").html("Checking files");
    $timecnt = 0;
    $analysisInt = window.setInterval(function(){
        switch( $timecnt % 3 ) {
        case 0:
            $("#uploadstatus").html("Checking files.");
            break;
        case 1:
            $("#uploadstatus").html("Checking files..");
            break;
        default:
            $("#uploadstatus").html("Checking files...");
        }
    $timecnt++;
    }, 1500);
}

/***********************************
 * Job process function
 ***********************************/
function processJob(e) {
    e.preventDefault();
    var myform = $("#upload")[0];
    $.ajax({
        url: "processJob.php",
        type: "POST",
        data: new FormData(myform),
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(data){
        data = JSON.parse(data);
        clearInterval($analysisInt);
        if (data["status"] == 0) {
            var info = "Done";
            $("#status").html(info);
            showResults(data);
            $("#uplink").removeClass("not-active");
        }
        else{
            $("#status").html(data["status_msg"]);
        }
    })
    .fail(function(data){
        data = JSON.parse(data);
        clearInterval($analysisInt);
        if (data["status"] != 0){
            $("#status").html(data["status_msg"]);
        }
    });
    $("#submit").addClass("not-active");
    $("#status").html("Analyzing data.......");
    $timecnt = 0;
    $analysisInt = window.setInterval(function(){
        switch( $timecnt % 6 ) {
        case 0:
            $("#status").html(".Analyzing data......");
            break;
        case 1:
            $("#status").html("..Analyzing data.....");
            break;
        case 2:
            $("#status").html("...Analyzing data....");
            break;
        case 3:
            $("#status").html("....Analyzing data...");
            break;
        case 4:
            $("#status").html(".....Analyzing data..");
            break;
        default:
            $("#status").html("......Analyzing data.");
        }
    $timecnt++;
    }, 1500);

    var jid = $("#jid").val();
    var resdir = "uploads/"+jid+"/results/";
    var html = '<h2 style="text-align:center">Results</h2>';
    html += '<div style="text-align:center;font-size:10px;font-style:italic;">(will appear below)</div><br/>';
    html += '<div id="jobid">Job ID '+
        jid + ' &#8212; <a target="_blank" href="'+resdir+'errLog.txt"><i>View log</i></a></div><br/>';
    $("#results").html(html);

}

/*************************************************
 * Display results after completion. First, build
 * HTML table for file links. Second, build HTML
 * image links.
 *************************************************/
function showResults(data) {
    // Build results HTML

    //****** Display file links section ******//
    var resdir = data["info"]["resultsdir"];
    var html = '<h2 style="text-align:center">Results</h2>';
    var jid = $("#jid").val();
    html += '<div id="jobid">Job ID '+
        jid + ' &#8212; <a target="_blank" href="'+resdir+'errLog.txt"><i>View log</i></a></div><br/>';
    html += '<table id="resultsfiles">';
    html += '<tr><th style="min-width:400px;">Tab-delimited files</th><th></th><th></th></tr>';
    for (i in data["txt"]) {
        var fn = data["txt"][i];
        $.each(fn, function(name, loc) {
            html += '<tr><td>'+name+'</td>';
            html += '<td><a class="filelink" target="_blank" href="'+resdir+loc+'">View</a></td>';
            html += '<td><a class="filelink" target="_blank" href="'+resdir+loc+'" download>Download</a></td></tr>';
        });
    }
    html += '</table><br/>';
    $("#results").html(html);

    //****** Display result images ******//
    if ($("#figs").prop("checked") == true) {
        html += '<div id="imgs">';
        // Raw growth curves
        for (var fn in data["imgs"]["rawgrowthcurves"]) {
            var src = resdir+data["imgs"]["rawgrowthcurves"][fn];
            html += '<div class="img">';
            html += '<h2 style="text-align:center;">'+fn+'</h2>';
            html += '<a class="imga" target="_blank" href="'+src+'"><img src="'+src+'" alt="Raw Growth Curves"></a>';
            html += '</div><br/>';
        }
        // Mean growth curves
        var src = resdir+data["imgs"]["meangrowthcurves"];
        html += '<div class="img">';
        html += '<h2 style="text-align:center;">Mean Growth Curves</h2>';
        html += '<a class="imga" target="_blank" href="'+src+'"><img src="'+src+'" alt="Mean Growth Curves"></a> ';
        html += '</div><br/>';
        //
        // Growth levels
        src = resdir+data["imgs"]["growthlevels"];
        html += '<div class="img">';
        html += '<h2 style="text-align:center;">Growth Levels</h2>';
        html += '<a class="imga" target="_blank" href="'+src+'"><img src="'+src+'" alt="Growth Levels">';
        html += '</div>';
        $("#results").html(html);
    }
}

