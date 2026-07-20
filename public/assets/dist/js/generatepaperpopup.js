var QuestionPanelID = 1;
var MainData;
var UrduHeading;
var EnglishHeading;
var totalMarks = $("#TotalMarks").val();
var totalMCQS = $("#TotalMCQS").val();
var SubjectId = $("#SubjectID").val();
var QueTypeIDForMCQS = 0;

var SelectedPanelData = {
    ListQuestions: null,
    ListMultipleOptions: null
};

function setTimer() {
    Loader = window.setTimeout(function () {
        Swal.fire({
            icon: 'error',
            title: 'Your internet connection are not responding. Kindly try again.'
        });
        $(".pp-loader").hide();
    }, 180000);
}

function clearTimer() {
    window.clearTimeout(Loader);
}

function Parent() {
    $("input:checkbox.Chapters").change(function () {
        $(this).siblings('ul')
            .find("input:checkbox.Topics")
            .prop('checked', this.checked);
    });
};

function ResetAll() {
    $("#GetTypes").val("");
    $("#selectedQuestion").html("");
    $("#TxtRequiredQuestions").val("");
    $("#TxtIgnoreQuestions").val("0");
    $("#TxtQuestionMarks").val("");
    $("#TxtBlankLines").val("0");
    $("#TxtForBlankLinessets").val("0");
    $("#chooseQuestionsByChapterIDs").html("");
    $("#counter").text("0");
    $("#Totalcounter").text("0");
    $("#AddQuestionPopup").attr("onclick", ("AddEditQuestions('')"));
    SelectedPanelData.ListQuestions = new Array();
    SelectedPanelData.ListMultipleOptions = new Array();
}

function GetQuestions(SelectedIDs) {
    //$(".pp-loader").show();
    setTimer();
    var SubTypeID = $("#GetTypes").val();
    if (SubTypeID == "" || SubTypeID == " " || SubTypeID == null) {
        Swal.fire({
            icon: 'error',
            title: 'Select question type!'
        });
        //$(".pp-loader").hide();
        return false;
    }

    var PeriorityID = $("#GetPeriority").val();
    if (PeriorityID == null || PeriorityID == "") {
        Swal.fire({
            icon: 'error',
            title: 'Select question periority option!'
        });
        //$(".pp-loader").hide();
        return false;
    }

    var RequiredQues = $("#TxtRequiredQuestions").val();
    if (RequiredQues == "" || RequiredQues == " " || RequiredQues == null) {
        Swal.fire({
            icon: 'error',
            title: 'Type required questions!'
        });
        //$(".pp-loader").hide();
        return false;
    }

    var QuestionMarks = $("#TxtQuestionMarks").val();
    if (QuestionMarks == "" || QuestionMarks == " " || QuestionMarks == null) {
        Swal.fire({
            icon: 'error',
            title: 'Type each question marks!'
        });
        //$(".pp-loader").hide();
        return false;
    }

    if ($("input:checkbox.Chapters:checked").length == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Select atleast one chapter!'
        });
        //$(".pp-loader").hide();
        return false;
    }
    else {
        TopicsIDs = "";
        $.each($("input:checkbox.Topics:checked"), function () {
            var ValueID = $(this).val();
            if (ValueID != undefined) {
                TopicsIDs += ValueID + ",";
            }
        });

        if (TopicsIDs != "") {
            TopicsIDs = TopicsIDs.substring(0, (TopicsIDs.length - 1));
        }
    }
    var SyllabusID = $("#SyllabusID").val();
    var ClassID    = $("#ClassID").val();
    var SubjectID  = $("#SubjectID").val();
    var medium = $("#Mediums").val();
    var ClassesData = "";
    $.ajax({
        url: baseurl + "admin/academics/papergenerate/getquestion",
        type: "POST",
        data: {'SyllabusID':SyllabusID,'ClassID':ClassID,'SubjectID':SubjectID,'TopicsIDs':TopicsIDs,'SubTypeID':SubTypeID,'PeriorityID':PeriorityID,'medium':medium},
        dataType: 'json',
        success: function(data) {
            if (data !== null) {
                ClassesData = ListQuestionsBindingHtml(data, true); // Bind Html
                UrduHeading = data.QuestionSubType.name_urdu;
                EnglishHeading = data.QuestionSubType.name_eng;
            }else {
                ClassesData = "<div class='alert alert-danger'>Sorry No Result Found. <br/> Please Select All Periority Types And Search Again.</div>";
            }
            $('#chooseQuestionsByChapterIDs').html("");
            $('#chooseQuestionsByChapterIDs').html(ClassesData);
            //Edit Mode Here
            // if (SelectedIDs !== "") {
            //     var ArraySelectedValues = SelectedIDs.split(',');
            //     if (ArraySelectedValues.length > 0) {
            //         for (var i = 0; i < ArraySelectedValues.length; i++) {
            //             if (ArraySelectedValues[i] !== "") {
            //                 CheckedSelectedValue($("#" + ArraySelectedValues[i]));
            //             }
            //         }
            //     }
            // }
            //$(".pp-loader").hide();
            clearTimer();
        }
    });
}

function ListQuestionsBindingHtml(data, Ajax) {
    MainData = data;
    //console.log(data)
    var QuestionsHtml = "";
    if (data.ListQuestions.length == 0) {
        Swal.fire({
            icon: 'info',
            title: 'Sorry No Result Found!',
            text: 'Please select all periority types and search again.'
        });
        $(".pp-loader").hide();
        return QuestionsHtml;
    }

    $("#Totalcounter").text(data.ListQuestions.length);
    var medium = $("#Mediums").val();
    var QueTypeID = data.ListQuestions[0].question_type;
    QueTypeIDForMCQS = QueTypeID;
    var SubType = $("#GetTypes").val();
    var TypeClassNameMain = "";
    var TypeClassName = "QuestionType";
    if (QueTypeID == 1) {
        TypeClassName = "MultipleOptions col-md-12"
    }else if (QueTypeID == 2 || QueTypeID == 3 || QueTypeID == 64 || QueTypeID == 5) {
        if (medium == 1) {
            TypeClassName = "col-md-12"
        }else if (medium == 2) {
            if (SubType == 19) {
                TypeClassName = "mycol-12"
            }else {
                if ($('#2Q').is(':checked')) {
                    TypeClassNameMain = "direction:rtl;"
                    TypeClassName = "col-md-6"
                }
                else {
                    TypeClassNameMain = "direction:rtl;"
                    TypeClassName = "col-md-12"
                }
            }
        }else if (medium == 3) {
            if ($('#2Q').is(':checked')) {
                TypeClassName = "col-md-6"
            }else {
                TypeClassName = "col-md-12"
            }
        }
    }else if (QueTypeID == 6) {
        if (medium == 2) {
            TypeClassNameMain = "direction:rtl;"
            TypeClassName = "col-md-6"
        }
        else if (medium == 3) {
            TypeClassName = "col-md-6"
        }
    }else if (QueTypeID == 4) {
        if (medium == 2) {
            TypeClassNameMain = "direction:rtl;"
            TypeClassName = "col-md-4"
        }
        else if (medium == 3) {
            TypeClassName = "col-md-4"
        }
    }else if (QueTypeID == 8 || QueTypeID == 46 || QueTypeID == 57 || QueTypeID == 59 || QueTypeID == 60 || QueTypeID == 61 || QueTypeID == 62 || QueTypeID == 156 || QueTypeID == 136 || QueTypeID == 48 || QueTypeID == 31 || QueTypeID == 26) {
        if (medium == 2) {
            TypeClassNameMain = "direction:rtl;"
            TypeClassName = "col-md-12"
        }else if (medium == 3) {
            TypeClassName = "col-md-12"
        }
    }else if (QueTypeID == 9) {
        TypeClassNameMain = "text-align:justify"
        TypeClassName = "col-md-12"
    }else if (QueTypeID == 11) {
        TypeClassNameMain = "text-align:justify"
        TypeClassName = "col-md-12"
    }

    QuestionsHtml += "<div class='row' style='" + TypeClassNameMain + "'>";
    $.each(data.ListQuestions, function (i, obj){
        QuestionsHtml += "<div class='" + TypeClassName + "'>";
        var OnclickFuntion = "CheckedSelectedValue(this)";
        var IDMurge = "";
        if (!Ajax) {
            OnclickFuntion = "";
            IDMurge = "Paste-";
        }
        QuestionsHtml += "<div class='row TableHover' id='" + IDMurge + obj.id + "' >";
        /*QuestionsHtml += "<div class='no-print'><span><a class='fa fa-up' onclick='Up();'  MainID=QuePanel-" + CurrentID + "  onclick='EditQuePanel(this)' ></a></span> <span><a class='fa fa-trash' MainID=QuePanel-" + CurrentID + " onclick='RemoveQuePanel(this)'></a></span></div>";*/
        var CounterID = (i + 1);

        // mcqs Questions Binding
        if (QueTypeID == 1) {
            var showDiv = false;
            if (SubType == 2) {
                //QuestionsHtml += " <div style='display:none;'><span>" + CounterID + "-" + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                //QuestionsHtml += "<div style='clear:both;'></div>"
                QuestionsHtml += "<ul style='padding:0px; margin:0px; width:100%;'>";
                var abcd = "";
                for (var j = 0; j < data.ListMultipleOptions.length; j++) {

                    if (j == 0) {
                        abcd = +CounterID + "." + "(A)";
                    }
                    else if (j == 1) {
                        abcd = "(B)";
                    }
                    else if (j == 2) {
                        abcd = "(C)";
                    }
                    else if (j == 3) {
                        abcd = "(D)";
                    }
                    var CorrectAnswersProperty = "";
                    if (data.ListQuestions[i].MultipleOptions[j].Answer) {
                        CorrectAnswersProperty = "class='correctAnswer EnglishMcqs' thiscorrect='" + (i + 1) + "-" + abcd + "' ";
                    }
                    QuestionsHtml += "<li " + CorrectAnswersProperty + " class='EnglishMcqs' ><span>" + abcd + "</span>" + data.ListQuestions[i].MultipleOptions[j].EmOption + "</li>";
                }
                QuestionsHtml += "</ul>";
            }
            else {
                if (medium == 1) {
                    QuestionsHtml += "<div class='col-6 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                    QuestionsHtml += "<div class='col-6 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].UrduQuestionDetails + "</div>"
                        + "<ul style='padding:0px; margin:0px; width:100%; direction:rtl;list-style: none;display:flex'>";
                }
                if (medium == 2) {
                    if ($('#2Q').is(':checked')) {
                        showDiv = true;
                        QuestionsHtml += "<div class='col-md-6 order-2 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                            + "<div class='col-md-6 order-1'><ul class='d-flex question_options_wrap list_style_none' style='padding:0px; margin:0px; width:100%; direction:rtl;'>";   
                    }
                    else {
                        QuestionsHtml += "<div class='col-md-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                            + "<ul class='d-flex question_options_wrap list_style_none' style='padding:0px; margin:0px; width:100%; direction:rtl;'>";
                    }
                }
                if (medium == 3) {
                    if ($('#2Q').is(':checked')) {
                        showDiv = true;
                        QuestionsHtml += "<div class='col-md-6 EnglishDiv d-flex'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                            + "<div class='col-md-6'><ul class='d-flex question_options_wrap list_style_none' style='padding:0px; margin:0px; width:100%; direction:ltr;'>";
                    }
                    else {
                        QuestionsHtml += "<div class='col-md-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                            + "<ul style='padding:0px; margin:0px; width:100%; direction:ltr;'>";
                    }
                }

                var abcd = "";
                var abcdval = "";
                //console.log(data.ListMultipleOptions)
                $.each(data.ListMultipleOptions, function (j, val){
                    if (j == 'e_opt_a') {
                        abcd = val;
                        abcdval = obj.e_opt_a;
                    }
                    else if (j == 'e_opt_b') {
                        abcd = val;
                        abcdval = obj.e_opt_b;
                    }
                    else if (j == 'e_opt_c') {
                        abcd = val;
                        abcdval = obj.e_opt_c;
                    }
                    else if (j == 'e_opt_d') {
                        abcd = val;
                        abcdval = obj.e_opt_d;
                    }
                    else if (j == 'u_opt_a') {
                        abcd = val;
                        abcdval = obj.u_opt_a;
                    }
                    else if (j == 'u_opt_b') {
                        abcd = val;
                        abcdval = obj.u_opt_b;
                    }
                    else if (j == 'u_opt_c') {
                        abcd = val;
                        abcdval = obj.u_opt_c;
                    }else if (j == 'u_opt_d') {
                        abcd = val;
                        abcdval = obj.u_opt_d;
                    }
                    var CorrectAnswersProperty = "";
                    if (medium == 1) {
                        if (data.ListQuestions[i].MultipleOptions[j].Answer) {
                            CorrectAnswersProperty = "class='correctAnswer DualMcqs' thiscorrect='" + (i + 1) + "-" + abcd + "' ";
                        }
                        if (data.ListQuestions[i].IsSameMcq != true) {
                            QuestionsHtml += "<li " + CorrectAnswersProperty + " class='DualMcqs'><span>" + abcd + "</span><div class='Ur'>" + data.ListQuestions[i].MultipleOptions[j].UmOption + "</div><div class='En'>" + data.ListQuestions[i].MultipleOptions[j].EmOption + "</div></li>";
                        }
                        else {
                            QuestionsHtml += "<li " + CorrectAnswersProperty + " class='DualMcqs'><span>" + abcd + "</span><div class='En'>" + data.ListQuestions[i].MultipleOptions[j].EmOption + "</div></li>";
                        }
                    }
                    if (medium == 2) {
                        if (obj.correcturdu == j) {
                            CorrectAnswersProperty = "class='correctAnswer UrduMcqs' thiscorrect='" + (i + 1) + "-" + val + "' ";
                        }
                        QuestionsHtml += "<li " + CorrectAnswersProperty + " class='UrduMcqs' ><span>" + abcd + "</span>" + abcdval + "</li>";
                    }
                    if (medium == 3) {
                        if (obj.correcteng == j) {
                            CorrectAnswersProperty = "class='correctAnswer EnglishMcqs' thiscorrect='" + (i + 1) + "-" + val + "' ";
                        }
                        QuestionsHtml += "<li " + CorrectAnswersProperty + " class='EnglishMcqs' ><span>" + abcd + "</span>" + abcdval + "</li>";
                    }
                });
                if (showDiv == true) {
                    QuestionsHtml += "</ul></div>";
                }
                else {
                    QuestionsHtml += "</ul>";
                }
            }
        }

        // Fill in the Blank Answer
        else if (QueTypeID == 3) {
            var AnswerTagsU = "";
            var AnswerTagsE = "";
            if (medium == 1) {
                AnswerTagsU = " class='FillBlank' fbanswer='" + data.ListQuestions[i].UrduFillAnswer + "-" + (i + 1) + "' ";
                AnswerTagsE = " class='FillBlank' fbanswer='" + data.ListQuestions[i].EnglishFillAnswer + "-" + (i + 1) + "' ";
                QuestionsHtml += "<div class='col-6'><div " + AnswerTagsE + "><div class='EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div></div></div>"
                QuestionsHtml += "<div class='col-6'><div " + AnswerTagsU + "><div class='UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].UrduQuestionDetails + "</div></div></div>"
            }
            if (medium == 2) {
                AnswerTagsU = " class='FillBlank' fbanswer='" + data.ListQuestions[i].correcturdu + "-" + (i + 1) + "' ";
                QuestionsHtml += "<div class='col-md-12'><div " + AnswerTagsU + "><div class='UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div></div></div>"
            }
            if (medium == 3) {
                AnswerTagsE = " class='FillBlank' fbanswer='" + data.ListQuestions[i].correcteng + "-" + (i + 1) + "' ";
                QuestionsHtml += "<div class='col-md-12'><div" + AnswerTagsE + "><div class='EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div></div></div>"
            }
        }

        // True False Binding
        else if (QueTypeID == 64) {
            if (medium == 1) {
                QuestionsHtml += "<div class='col-12 trueFalseQ' qstatus='" + data.ListQuestions[i].correcturdu + data.ListQuestions[i].correcteng + "-" + (i + 1) + "'>"
                QuestionsHtml += "<div class='row'><div class='col-6 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                QuestionsHtml += "<div class='col-6 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div></div>"
                QuestionsHtml += "</div>";
            }
            if (medium == 2) {
                QuestionsHtml += "<div class='trueFalseQ' qstatus='" + data.ListQuestions[i].correcturdu + "-" + (i + 1) + "'>"
                QuestionsHtml += " <div class='col-md-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                QuestionsHtml += "</div>";
            }
            if (medium == 3) {
                QuestionsHtml += "<div class='trueFalseQ' qstatus='" + data.ListQuestions[i].correcteng + "-" + (i + 1) + "'>"
                QuestionsHtml += " <div class='col-md-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                QuestionsHtml += "</div>";
            }
        }

        // Match Columns
        else if (QueTypeID == 5) {
            if (medium == 1) {
                QuestionsHtml += " <div class='col-6 MatchColumns EnglishDiv'>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                QuestionsHtml += " <div class='col-6 MatchColumns UrduDiv'>" + data.ListQuestions[i].UrduQuestionDetails + "</div>"
            }
            if (medium == 2) {
                QuestionsHtml += " <div class='col-md-12 MatchColumns UrduDiv'>" + data.ListQuestions[i].questionurdu + "</div>"
            }
            if (medium == 3) {
                QuestionsHtml += " <div class='col-md-12 MatchColumns EnglishDiv'>" + data.ListQuestions[i].questioneng + "</div>"
            }
        }

        // Questions Answers
        else if (QueTypeID == 8 || QueTypeID == 46 || QueTypeID == 57 || QueTypeID == 59 || QueTypeID == 60 || QueTypeID == 61 || QueTypeID == 62 || QueTypeID == 156 || QueTypeID == 136 || QueTypeID == 48 || QueTypeID == 31 || QueTypeID == 26) {
            if (medium == 1) {
            }
            if (medium == 2) {
                QuestionsHtml += " <div class='col-md-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                if ($("#TxtForBlankLinessets").val() > 0) {
                    QuestionsHtml += "<div class='col-md-12 lineOuterFour'>"
                    var Value = $("#TxtForBlankLinessets").val();
                    for(var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                    }
                    QuestionsHtml += "</div>"
                }
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-md-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' />"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            if (medium == 3) {
                QuestionsHtml += " <div class='col-md-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                if ($("#TxtForBlankLinessets").val() > 0) {
                    QuestionsHtml += "<div class='col-md-12 lineOuterFour'>"
                    var Value = $("#TxtForBlankLinessets").val();
                    for(var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFour' />"
                        QuestionsHtml += "<hr class='blankLineFourscp' />"
                    }
                    
                    QuestionsHtml += "</div>"
                }
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-md-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' />"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }
        
        // One Words Binding
        else if (QueTypeID == 11) {
            if(medium == 2){
                QuestionsHtml += " <div class='col-md-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-md-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            else if (medium == 3) {
                QuestionsHtml += " <div class='col-md-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }
        // Two Words Binding
        else if (QueTypeID == 6) {
            if(medium==2){
                QuestionsHtml += " <div class='col-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].UrduQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            else if (medium == 3) {
                QuestionsHtml += " <div class='col-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }

        // Three Words Binding
        else if (QueTypeID == 4) {
            if (medium == 2) {
                QuestionsHtml += " <div class='col-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questionurdu + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            else if (medium == 3) {
                QuestionsHtml += " <div class='col-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].questioneng + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }

        // Five Words Binding
        else if (QueTypeID == 8) {
            if (medium == 2) {
                QuestionsHtml += " <div class='col-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].UrduQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            else if (medium == 3) {
                QuestionsHtml += " <div class='col-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }

        // Paragraphs
        else if (QueTypeID == 9) {
            if (medium == 2) {
                QuestionsHtml += " <div class='col-12 UrduDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].UrduQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
            if (medium == 3) {
                QuestionsHtml += " <div class='col-12 EnglishDiv'><span>" + CounterID + "." + "</span>" + data.ListQuestions[i].EnglishQuestionDetails + "</div>"
                if ($("#TxtBlankLines").val() > 0) {
                    QuestionsHtml += "<div class='col-12 lineOuter'>"
                    var Value = $("#TxtBlankLines").val();
                    for (var b = 0; b < Value; b++) {
                        QuestionsHtml += "<hr class='blankLine' style='display:none'/>"
                    }
                    QuestionsHtml += "</div>"
                }
            }
        }
        if (SubType == 13) {
            QuestionsHtml += "</div><span class='no-print sortIcons'><a href='javascript:void(0);' onclick='Up(this)'><i class='fa fa-up'></i></a> <a href='javascript:void(0);' onclick='Down(this)'><i class='fa fa-down'></i></a></span>";
            QuestionsHtml += "</div>";
        }
        else {
            QuestionsHtml += "</div></div>";
        }
    });
    QuestionsHtml += "</div>";
    return QuestionsHtml;
}
$(document).on('click', '.QuestionDiv > div > div', function () {
    var TotalQuestions = parseInt($("#TxtRequiredQuestions").val());
    var Selectedquestion = $('.QuestionDiv .SelectedQuestion').length;
    if($(this).children('.TableHover').hasClass('SelectedQuestion')){
       let currID = $(this).children('.TableHover').attr('id')
       $(this).children('.TableHover').removeClass('SelectedQuestion')
       $('.SelectedDiv').find(`#${currID}`).remove()
    }else{
        if(Selectedquestion >= TotalQuestions){
            alert("Total Selected Question Limit " + TotalQuestions + ".");
            return false;
        }else{
            SelectedPanelData.ListQuestions = new Array();
            SelectedPanelData.ListMultipleOptions = MainData.ListMultipleOptions;
            $(this).children('.TableHover').addClass("SelectedQuestion");
            $('.QuestionDiv').find('.SelectedQuestion').each(function () {
                var CurrentID = $(this).attr('id');
                //SelectedPanelData.ListQuestions.push($(this).attr('id'));
                if (MainData.ListQuestions.length > 0) {
                    for (var i = 0; i < MainData.ListQuestions.length; i++) {
                        if (parseInt(MainData.ListQuestions[i].id) == parseInt(CurrentID)) {
                            SelectedPanelData.ListQuestions.push(MainData.ListQuestions[i]);
                        }
                    }
                }
            });
            //SelectedPanelData.ListMultipleOptions.push(MainData.ListMultipleOptions);
        //   console.log(MainData.MultipleOptions);
        //   console.log(SelectedPanelData);
            $('.SelectedDiv').append($(this).html());
        }
    }
    
})


function SelectedItemGetByClass(Div) {
    var Html = "";
    var TotalQuestions = parseInt($("#TxtRequiredQuestions").val());
    $('.QuestionDiv .SelectedQuestion').each(function () {
        Html += $(this)[0].outerHTML;
        var CurrentID = $(this).attr("ID");
        console.log(CurrentID+"VVVVVVVV");
        if (MainData.ListQuestions.length > 0) {
            for (var i = 0; i < MainData.ListQuestions.length; i++) {
                if (parseInt(MainData.ListQuestions[i].QuestionID) == parseInt(CurrentID)) {
                        if (!$(Div).hasClass("SelectedQuestion") && SelectedPanelData.ListQuestions != null && SelectedPanelData.ListQuestions.length == TotalQuestions) {
                            alert("Total Selected Question Limit " + TotalQuestions + ".");
                            return false;
                        }
                        else{
                        SelectedPanelData.ListQuestions.push(MainData.ListQuestions[i]);
                            $(Div).toggleClass("SelectedQuestion");
                        }
                }
            }
        }
    });
    $("#selectedQuestion").html(Html);
    if (SelectedPanelData.ListQuestions != null) {
        $("#counter").text(SelectedPanelData.ListQuestions.length);
    }
    else {
        $("#counter").text("0");
    }
}

function AddEditQuestions(EditID) {
    var TotalQuestions = parseInt($("#TxtRequiredQuestions").val());
    if (SelectedPanelData.ListQuestions == null || SelectedPanelData.ListQuestions.length < TotalQuestions ) {
        Swal.fire({
            icon: 'info',
            title: 'Please select ' + TotalQuestions + ' Questions'
        });
        return false;
    }
    var HtmlBind = "";
    var queType = $("#GetTypes").val();
    var priorityType = $("#GetPeriority").val();
    var medium = $("#Mediums").val();
    var requiredQues = $("#TxtRequiredQuestions").val();
    var ignoreQues = $("#TxtIgnoreQuestions").val();
    var questionMarks = $("#TxtQuestionMarks").val();
    var blankLines = $("#TxtBlankLines").val();
    var TxtForBlankLinessets = $("#TxtForBlankLinessets").val();
    var solveQues = requiredQues - ignoreQues;

    var SelectedIDs = "";
    for (var i = 0; i < SelectedPanelData.ListQuestions.length; i++) {
        SelectedIDs += SelectedPanelData.ListQuestions[i].id + ",";
    }
    var CurrentID = QuestionPanelID;
    if (EditID != "") {
        CurrentID = parseInt(EditID.split("-")[1]);
    }

    if (EditID == "") {
        var qm = parseInt(questionMarks);
        var sq = parseInt(solveQues);
        var calc = qm * sq;
        totalMarks = parseInt(totalMarks + calc);
        $("#TotalMarks").val(totalMarks);
/*        totalMarks = parseInt(totalMarks + questionMarks * solveQues);*/
        if (QueTypeIDForMCQS == 1) {
            var a = parseInt(totalMCQS);
            var b = parseInt(requiredQues);
            var c = a + b;
            totalMCQS = c;
        }
        HtmlBind += "<div id=QuePanel-" + CurrentID + " queType=" + queType + " requiredQues=" + requiredQues + " ignoreQues=" + ignoreQues + " questionMarks=" + questionMarks + " blankLines=" + blankLines + " medium=" + medium + " SelectedIDs=" + SelectedIDs + " priorityType=" + priorityType + ">";
    }
    else {
        $("#QuePanel-" + CurrentID).attr("queType", queType);
        $("#QuePanel-" + CurrentID).attr("priorityType", priorityType);
        $("#QuePanel-" + CurrentID).attr("requiredQues", requiredQues);
        $("#QuePanel-" + CurrentID).attr("ignoreQues", ignoreQues);
        $("#QuePanel-" + CurrentID).attr("questionMarks", questionMarks);
        $("#QuePanel-" + CurrentID).attr("blankLines", blankLines);
        $("#QuePanel-" + CurrentID).attr("medium", medium);
        $("#QuePanel-" + CurrentID).attr("SelectedIDs", SelectedIDs);
    }

    if (medium == 1) {
        if (ignoreQues == "" || ignoreQues == " " || ignoreQues == null || ignoreQues == 0) {
            HtmlBind += "<div class='row heading'><div class='col-6 EngHeading'>" + "Q" + CurrentID + ". " + EnglishHeading + "</div>"
                + "<div class='col-1 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div>"
                + "<div class='col-5 UrdHeading'>سوال نمبر" + CurrentID + "." + UrduHeading + "</div></div>";
        }
        else {
            HtmlBind += "<div class='row heading'><div class='col-6 EngHeading'>" + "Q" + CurrentID + ". " + EnglishHeading + " (Any " + solveQues + ")</div>"
                + "<div class='col-1 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div>"
                + "<div class='col-5 UrdHeading'>سوال نمبر" + CurrentID + "." + UrduHeading + "(کوئی سے" + solveQues +")</div></div>";
        }
    }
    else if (medium == 2) {
        if (ignoreQues == "" || ignoreQues == " " || ignoreQues == null || ignoreQues == 0) {
            HtmlBind += "<div class='row heading'><div class='col-2 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div>"
                     + "<div class='col-10 UrdHeading'>سوال نمبر" + CurrentID + "." + UrduHeading + "</div></div>";
        }
        else {
            HtmlBind += "<div class='row heading'><div class='col-2 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div>"
                + "<div class='col-10 UrdHeading'>سوال نمبر" + CurrentID + "." + UrduHeading + "(کوئی سے" + solveQues + ")</div></div>";
        }
    }
    else if (medium == 3) {
        if (ignoreQues == "" || ignoreQues == " " || ignoreQues == null || ignoreQues == 0) {
            HtmlBind += "<div class='row heading'><div class='col-md-10 EngHeading'>" + "Q" + CurrentID + ". " + EnglishHeading + "</div>"
                + "<div class='col-md-2 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div></div>";
        }
        else {
            HtmlBind += "<div class='row heading'><div class='col-10 EngHeading'>" + "Q" + CurrentID + ". " + EnglishHeading + " (Any " + solveQues + ")</div>"
                + "<div class='col-2 CalcMarks'>" + questionMarks + "X" + solveQues + "=" + (questionMarks * solveQues) + "</div></div>";
        }
    }
    /*shuffleArr(SelectedPanelData.ListQuestions);*/
    HtmlBind += ListQuestionsBindingHtml(SelectedPanelData, false);
    HtmlBind += "<div class='no-print udButtons'><span><a class='fa fa-edit' data-bs-toggle='modal' data-bs-target='#QuestionsModal'  MainID=QuePanel-" + CurrentID + "  onclick='EditQuePanel(this)' ></a></span> <span><a class='fa fa-trash' MainID=QuePanel-" + CurrentID + " onclick='RemoveQuePanel(this)'></a></span></div>";
    if (EditID == "") {
        HtmlBind += "</div>";
        if (queType == 13) {
            if ($('#MakeParts').is(':checked')) {
                var abc = "";
                QuestionPanelID--;
                for (var i = 0; i <= requiredQues; i++) {
                    if (i % 2 != 0) {
                        QuestionPanelID++;
                        abc = "(A)"
                    }
                    else {
                        abc = "(B)"
                    }
                    HtmlBind = HtmlBind.replace(new RegExp("<span>" + i + ".</span>", "g"), "<span>" + QuestionPanelID + abc + ".</span>");
                }
            }
        }
        $("#QuestionPanelDiv").append(HtmlBind);
    }
    else {
        $("#QuePanel-" + CurrentID).html(HtmlBind);
    }
    QuestionPanelID++;
    ResetAll();
    GetAllCorrectAnswers();
    return true;
}

function shuffleArr(array) {
    
    for (var i = array.length - 1; i > 0; i--) {
        var rand = Math.floor(Math.random() * (i + 1));
        [array[i], array[rand]] = [array[rand], array[i]]
    }
    return array;
}

function EditQuePanel(Panel) {
    ResetAll();
    var PanelID = "#" + $(Panel).attr("MainID");
    var queType = $(PanelID).attr("queType");
    var priorityType = $(PanelID).attr("priorityType");
    var requiredQues = $(PanelID).attr("requiredQues");
    var ignoreQues = $(PanelID).attr("ignoreQues");
    var questionMarks = $(PanelID).attr("questionMarks");
    var blankLines = $(PanelID).attr("blankLines");
    var TxtForBlankLinessets = $(PanelID).attr("TxtForBlankLinessets");
    var medium = $(PanelID).attr("medium");
    var SelectedIDs = $(PanelID).attr("SelectedIDs");
    var periority = (priorityType);
    var periorityArray = periority.split(",");
    $("#GetTypes").val(queType);
    $("#GetPeriority").val(periorityArray);
    $("#TxtRequiredQuestions").val(requiredQues);
    $("#TxtIgnoreQuestions").val(ignoreQues);
    $("#TxtQuestionMarks").val(questionMarks);
    $("#TxtBlankLines").val(blankLines);
    $("#TxtForBlankLinessets").val(TxtForBlankLinessets);
    $("#Mediums").val(medium);
    $("#GetPeriority").multiselect("refresh");

    GetQuestions(SelectedIDs);

    $("#AddQuestionPopup").attr("onclick", ("AddEditQuestions('" + $(Panel).attr("MainID") + "')"));
}

function RemoveQuePanel(Panel) {
    if (confirm("Are you sure ?")) {
        $(("#" + $(Panel).attr("MainID"))).remove();
    }
    GetAllCorrectAnswers();
}

function SelectRandom() {
    SelectedPanelData.ListQuestions = new Array();
    SelectedPanelData.ListMultipleOptions = new Array();
    $("#selectedQuestion").html("");
    /*var questionType = $("#GetTypes").val();*/
    $("#counter").text(0);
    var filterType = "div";
    var Allli = $("#chooseQuestionsByChapterIDs " + filterType + '.SelectedQuestion');
    for (var i = 0; i < Allli.length; i++) {
        $(Allli[i]).removeClass("SelectedQuestion");
    }
    var TotalQuestions = parseInt($("#TxtRequiredQuestions").val());
    var randomElements = jQuery("#chooseQuestionsByChapterIDs " + filterType + '.TableHover').get().sort(function () {
        return Math.round(Math.random()) - 0.5
    }).slice(0, TotalQuestions);
    questioncount = TotalQuestions;
    for (var i = 0; i < randomElements.length; i++) {
        CheckedSelectedValue($(randomElements[i])[0]);
    }
}

function GetAllCorrectAnswers() {
    $("#MultiAnswerSheet").html("");
    $("#TrueFalseAnswerSheet").html("");
    $("#FillBlankAnswerSheet").html("");
    if ($(".correctAnswer").length) {
        var CorrectAnswershtml = "";
        CorrectAnswershtml += "<h6 style='text-align:center; font-weight:bold;'>Multiple Choice Correct Answers</h6><ul style='width:100%; margin:0px; padding:0px;'>";
        $(".correctAnswer").each(function () {
            var getAttr = $(this).attr("thiscorrect");
            var sperateValue = getAttr.split("-");
            var QuestionNo = sperateValue[0];
            var AnswerNo = sperateValue[1];
            CorrectAnswershtml += "<li style='width:5%; display:inline-block; border-top:1px solid #fff; border-bottom:1px solid #fff; font-size:15px; background-color:#000; color:#fff; text-align:center;'>" + QuestionNo + "</li>" +
                                  "<li style='width:5%; display:inline-block; border-top:1px solid #000; border-bottom:1px solid #000; font-size:15px; background-color:#fff; color:#000; text-align:center;'>" + AnswerNo + "</li>";
        });
        CorrectAnswershtml += "</ul><br />";
        $("#MultiAnswerSheet").html(CorrectAnswershtml);
    }

    if ($(".trueFalseQ").length) {
        var TrueFalseAnswershtml = "";
        TrueFalseAnswershtml += "<h6 style='text-align:center; font-weight:bold;'>True / False Correct Answers</h6><ul style='width:100%; margin:0px; padding:0px;'>";
        $(".trueFalseQ").each(function () {
            var getAttr = $(this).attr("qstatus");
            var sperateValue = getAttr.split("-");
            var Answer = sperateValue[0];
            var QuestionNo = sperateValue[1];
            var Image = "";
            if (Answer == "true") {
                Image = "<i class='fa fa-check'></i>"
            }
            else {
                Image = "<i class='fa fa-times'></i>"
            }
            TrueFalseAnswershtml += "<li style='width:5%; display:inline-block; border-top:1px solid #fff; border-bottom:1px solid #fff; font-size:15px; background-color:#000; color:#fff; text-align:center;'>" + QuestionNo + "</li>" +
                                    "<li style='width:5%; display:inline-block; border-top:1px solid #000; border-bottom:1px solid #000; font-size:15px; background-color:#fff; color:#000; text-align:center;'>" + Image + "</li>";
        });
        TrueFalseAnswershtml += "</ul><br />";
        $("#TrueFalseAnswerSheet").html(TrueFalseAnswershtml);
    }

    if ($(".FillBlank").length) {
        var FilBlanksAnswershtml = "";
        FilBlanksAnswershtml += "<h6 style='text-align:center; font-weight:bold;'>Fill In The Blanks Correct Answers</h6><ul style='width:100%; margin:0px; padding:0px;'>";
        $(".FillBlank").each(function () {
            var getAttr = $(this).attr("fbanswer");
            var sperateValue = getAttr.split("-");
            var Answer = sperateValue[0];
            var QuestionNo = sperateValue[1];
            FilBlanksAnswershtml += "<li style='width:5%; display:inline-block; border-top:1px solid #fff; border-bottom:1px solid #fff; font-size:15px; background-color:#000; color:#fff; text-align:center;'>" + QuestionNo + "</li>" +
                                    "<li style='width:15%; display:inline-block; border-top:1px solid #000; border-bottom:1px solid #000; font-size:12pt; background-color:#fff; color:#000; text-align:center;'>" + Answer + "</li>";
        });
        FilBlanksAnswershtml += "</ul><br />";
        $("#FillBlankAnswerSheet").html(FilBlanksAnswershtml);
    }
}

function SavePaper() {
    $("#PaperHtml").val($("#QuestionPanelDiv").html());
    $("#AnswersHtml").val($("#AnswerPanelDiv").html());
    $("#TotalMCQS").val(totalMCQS);
    $('#SaveModel').modal('hide');
}

function PrintPaperSingle() {
    $("#QuestionsHtmlSingle").val($("#QuestionPanelDiv").html());
    $("#AnswersHtmlSingle").val($("#AnswerPanelDiv").html());
    $("#TotalMarksSingle").val(totalMarks);
    $("#TotalMCQSSingle").val(totalMCQS);
    document.getElementById('PrintSingle').submit();
    return true;
}

function PrintPaperDouble() {
    $("#QuestionsHtmlDouble").val($("#QuestionPanelDiv").html());
    $("#AnswersHtmlDouble").val($("#AnswerPanelDiv").html());
    $("#TotalMarksDouble").val(totalMarks);
    $("#TotalMCQSDouble").val(totalMCQS);
    document.getElementById('PrintDouble').submit();
    return true;
}

function PrintPaperHalf() {
    $("#QuestionsHtmlHalf").val($("#QuestionPanelDiv").html());
    $("#AnswersHtmlHalf").val($("#AnswerPanelDiv").html());
    $("#TotalMarksHalf").val(totalMarks);
    $("#TotalMCQSHalf").val(totalMCQS);
    document.getElementById('PrintHalf').submit();
    return true;
}