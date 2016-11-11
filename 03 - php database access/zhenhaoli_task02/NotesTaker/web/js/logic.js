$(document).ready(function(){

    $('.modal').modal();

    $("#newNoteForm").hide();

    $("#addNewNote").click(function(){
        if($("#newNoteForm").is(':visible')){
            $("#newNoteForm").slideUp();
        } else {
            $("#newNoteForm").slideDown();
        }
    });

    $("#saveNote").click(function(){
        $("#newNoteForm").slideUp();
    });

    $("#cancelNote").click(function(){
        $("#newNoteForm").slideUp();
    });

    $(".card").click(function () {
        if($(this).hasClass('white')) {
            $(this).removeClass("white");
            $(this).addClass("blue");
            $(this).children().first().removeClass('black-text');
            $(this).children().first().addClass('white-text');
        } else {
            $(this).removeClass("blue");
            $(this).addClass("white");
            $(this).children().first().removeClass('white-text');
            $(this).children().first().addClass('black-text');
        }
    });

    $(".edit").click(function () {
        var title = $(this)
            .parent()
            .clone()
            .children()
            .remove()
            .end()
            .text().replace(/\s/g,'');

        var content = $(this).parent().next().text();


        $('#modal1').modal('open');
        $("#editnote").val($(this).attr('id'));
        $("#newtitle").val(title);
        $("#newcontent").val(content);
    });


    $(".delete").click(function () {
        $("#deletenote").val($(this).attr('id'));
        $("#delete-note").submit();
    });

    $("#updateNote").click(function () {
        $('#modal1').modal('close');
    });


});