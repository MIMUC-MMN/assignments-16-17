$(document).ready(manipulateDOM);

function manipulateDOM() {
    renderStaticContent();
    renderPasswordAnalysis();
    registerEventHandlers();
}

function renderStaticContent() {
    $('body').append(`
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Password Strength</a>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="password">Password:</label>
                <input id="password" type="password" class="form-control success" placeholder="Analyze a password">
            </div>
        </div>
    </div>

</div>
    `);
}

function renderPasswordAnalysis() {

    $('.container').append(`
<div id="password-analysis" class="row">

   <div class="row">
       <div class="col-sm-6 col-sm-offset-3">
           <div id="score-in-bar"></div>
       </div>
   </div>

   <h3 class="text-center" id="feedback-warning"></h3>

   <p>If this password were used for on online banking site, it could probably be cracked within <span id="crack-bank"></span></p>

   <p>If this password would leak as encrypted string, it could probably be cracked within <span id="crack-leaked"></span></p>

   <ul id="feedback-suggestions"></ul>

   <pre>
       <div id="print-json"></div>
   </pre>

</div>

    `).children().last().hide();

    renderPasswordBars();
}

function renderPasswordBars() {
    $('#score-in-bar div').remove();

    let pwFieldWidth = $('#password').width();
    let margin = 5;
    let barWidth = (pwFieldWidth-5*2) / 5;

    const bar = $("<div>");

    bar.css({
        "display": "inline-block",
        "width": barWidth,
        "height": "15px",
        "background-color": "grey",
        "border-radius": "5px",
        "margin": "0px " + margin+"px"
    });

    $('#score-in-bar').append(bar.clone());
    $('#score-in-bar').append(bar.clone());
    $('#score-in-bar').append(bar.clone());
    $('#score-in-bar').append(bar.clone());
    $('#score-in-bar').append(bar.clone());

}

function registerEventHandlers() {
    $('#password').keyup(setValuesOnDOM);
    $(window).resize(renderPasswordBars);
}

function resetState() {
    $('#score-in-bar div').css( "backgroundColor", "grey" );
    $('#feedback-suggestions li').remove();
}

function setValuesOnDOM() {

    resetState();

    var analysis = zxcvbn($(this).val());

    if(!analysis.password) {
        $('#password-analysis').hide();
        return;
    }
    else $('#password-analysis').show();

    console.log(analysis);

    switch (analysis.score){
        case 0: $("#score-in-bar div:lt(1)" ).css( "backgroundColor", "#BF1251" ); break;
        case 1: $("#score-in-bar div:lt(2)" ).css( "backgroundColor", "#FF1C14" ); break;
        case 2: $("#score-in-bar div:lt(3)" ).css( "backgroundColor", "yellow" ); break;
        case 3: $("#score-in-bar div:lt(4)" ).css( "backgroundColor", "#d2ff4d" ); break;
        case 4: $("#score-in-bar div:lt(5)" ).css( "backgroundColor", "#33cc33" ); break;
        default: $("#score-in-bar div" ).css( "backgroundColor", "grey" ); break;
    }

    $('#feedback-warning').text(analysis.feedback.warning);

    $('#crack-bank').text(analysis.crack_times_display.online_throttling_100_per_hour);
    $('#crack-leaked').text(analysis.crack_times_display.offline_slow_hashing_1e4_per_second);


    analysis.feedback.suggestions.forEach(s=> {
        $('#feedback-suggestions').append('<li> '+s+'</li>');
    });

    $('#print-json').text(JSON.stringify(analysis, null, 2));

}
