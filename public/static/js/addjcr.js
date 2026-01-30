$(document).ready(function () {
    $('#id_lastcirc_from, #id_lastcirc_to').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        sideBySide: true,
        toolbarPlacement: 'bottom',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-arrow-up',
            down: 'fa fa-arrow-down',
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            today: 'fa fa-calendar-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    $("#id_lastcirc_from").on("dp.change", function (e) {
        $('#id_lastcirc_to').data("DateTimePicker").minDate(e.date);
    });
    $("#id_lastcirc_to").on("dp.change", function (e) {
        $('#id_lastcirc_from').data("DateTimePicker").maxDate(e.date);
    });

    $('#id_jobDate, #id_workOrderDate,  #id_shoeDate, #id_assembled_date, #id_depOffice_date, #id_arrivalSite_date, #id_indented_date, #id_wellReadiness_date, #id_wellTaken_date, #id_rigUP_date, #id_wellHandOver_date, #id_depSite_date, #id_arrivalOffice_date').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-arrow-up',
            down: 'fa fa-arrow-down',
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            today: 'fa fa-calendar-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    $("#id_assembled_date").on("keyup change click dp.change", function () {
        const dateValue = $('#id_assembled_date').val();
        $('#id_depOffice_date').val(dateValue);
        $('#id_arrivalSite_date').val(dateValue);
        $('#id_indented_date').val(dateValue);
        $('#id_wellReadiness_date').val(dateValue);
        $('#id_wellTaken_date').val(dateValue);
        $('#id_rigUP_date').val(dateValue);
        $('#id_wellHandOver_date').val(dateValue);
        $('#id_depSite_date').val(dateValue);
        $('#id_arrivalOffice_date').val(dateValue);
    })

    $('#id_assembled_time, #id_depOffice_time, #id_arrivalSite_time, #id_indented_time, #id_wellReadiness_time, #id_wellTaken_time, #id_rigUP_time, #id_wellHandOver_time, #id_depSite_time, #id_arrivalOffice_time').datetimepicker({
        format: 'HH:mm',
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-arrow-up',
            down: 'fa fa-arrow-down',
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            today: 'fa fa-calendar-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    

    const formElements = document.querySelectorAll('#msform .form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const submitButton = document.querySelector('button[type="submit"]');
    var currentTab = 0;

    progressSteps.forEach((step, index) => {
        step.addEventListener('click', () => {
            formElements.forEach((formElement) => {
                formElement.style.display = 'none';
                formElement.style.opacity = 0;
            });

            // progressSteps.forEach((progressStep) => {
            //     progressStep.classList.remove('active');
            // });
            progressSteps[index].classList.add('active');
            formElements[index].style.display = 'block';
            formElements[index].style.opacity = 1;
            step.classList.add('active');

            var x, y, z, i, valid = true;
            x = document.getElementsByClassName("form-step");
            if (x[currentTab].getElementsByTagName("input") !== null) {
                y = x[currentTab].getElementsByTagName("input");
                // A loop that checks every input field in the current tab:
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].required && y[i].value === "") {
                        // add an "invalid" class to the field:
                        y[i].classList.add("invalid");
                        // and set the current valid status to false
                        valid = false;
                    }
                    else {
                        y[i].classList.remove("invalid");
                    };
                }
            }
            if (x[currentTab].getElementsByTagName("select") !== "") {
                z = x[currentTab].getElementsByTagName("select");
                for (i = 0; i < z.length; i++) {
                    // If a field is empty...
                    if (z[i].required && z[i].value === "") {
                        // add an "invalid" class to the field:
                        z[i].classList.add("invalid");
                        // and set the current valid status to false
                        valid = false;
                    }
                    else {
                        z[i].classList.remove("invalid");
                    }
                }
            }

            // If the valid status is true, mark the step as finished and valid:
            if (!valid) {
                $("#progressbar li strong")[currentTab].className = 'invalid';
            }
            else {
                $("#progressbar li strong")[currentTab].classList.remove('invalid');
            }
            currentTab = index;
            if (index === formElements.length - 1) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        });

        // Set the first step as active initially
        progressSteps[0].classList.add('active');
        // formElements[0].style.display = 'flex';
    });

    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;

    x = document.getElementsByClassName("form-step");
    if (x[currentTab].getElementsByTagName("input") !== null) {
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].required && y[i].value !== "") {
                // add an "invalid" class to the field:
                y[i].classList.remove("invalid");
            };
        }
    }
    if (x[currentTab].getElementsByTagName("select") !== "") {
        z = x[currentTab].getElementsByTagName("select");
        for (i = 0; i < z.length; i++) {
            // If a field is empty...
            if (z[i].required && z[i].value !== "") {
                // add an "invalid" class to the field:
                z[i].classList.remove("invalid");
            }
        }
    }

    $(".next").click(function (event) {
        event.preventDefault();
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        var x, y, z, i, valid = true;
        x = document.getElementsByClassName("form-step");
        if (x[currentTab].getElementsByTagName("input") !== null) {
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].required && y[i].value === "") {
                    // add an "invalid" class to the field:
                    y[i].classList.add("invalid");
                    // and set the current valid status to false
                    valid = false;
                }
                else {
                    y[i].classList.remove("invalid");
                };
            }
        }
        if (x[currentTab].getElementsByTagName("select") !== "") {
            z = x[currentTab].getElementsByTagName("select");
            for (i = 0; i < z.length; i++) {
                // If a field is empty...
                if (z[i].required && z[i].value === "") {
                    // add an "invalid" class to the field:
                    z[i].classList.add("invalid");
                    // and set the current valid status to false
                    valid = false;
                }
                else {
                    z[i].classList.remove("invalid");
                }
            }
        }

        // If the valid status is true, mark the step as finished and valid:
        if (!valid) {
            $("#progressbar li strong")[currentTab].className = 'invalid';
            if (currentTab === formElements.length - 2) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }
        submitButton.disabled = false;

        //Add Class Active
        $("#progressbar li").eq($("#msform .form-step").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({ 'opacity': opacity });
            },
            duration: 600
        });
        currentTab += 1;
        // };
        window.scroll(0, 0);
    });

    $(".previous").click(function (event) {
        event.preventDefault();
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        $("#progressbar li").eq($("fieldset").index(previous_fs)).addClass("active");
        //show the previous fieldset
        previous_fs.show();
        // previous_fs.addClass('select form-select')
        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({ 'opacity': opacity });
            },
            duration: 600
        });
        currentTab -= 1;
        window.scroll(0, 0);
        submitButton.disabled = true;
    });

    $('.radio-group .radio').click(function () {
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
    });

    $('#wrapper').on('click', '.personnelselect', function () {
        var lastid = this.id;
        $.ajax({
            url: '/jcr/ajaxcalls/users',
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $.each(response, function (key, value) {
                    $('#' + lastid).not(':first').remove();
                    if ($('#' + lastid + ' > option').length <= Object.keys(response).length)
                        if ($("#" + lastid + " > option[value='" + value['id'] + "']").length == 0)
                            $('<option>').val(value["id"]).text(capitalizeEachWord(value["name"])).appendTo('#' + lastid);
                });
            }
        });
    });

    function capitalizeEachWord(sentence) {
        return sentence.split(' ').map(word => {
            if (word.length === 0) {
                return ''; // Handle empty strings if present
            }
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        }).join(' ');
    }

    $('#add_more_personnel').click(function (event) {
        event.preventDefault();
        // last <div> with element class id
        var lastid = $(".element:last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[1]) + 1;
        // Adding new div container after last occurance of element class
        $(".element:last").after("<div class='mb-3 element' id='userinlinemodel_" + nextindex + "'></div>");
        // Adding element to <div>
        $("#userinlinemodel_" + nextindex).append("<label for='select_" + nextindex + "' class='form-label requiredField' id='personnellabel_" + nextindex + "'>Personnel<span class='asteriskField' id='asterisk_" + nextindex + "'>*</span></label><select data-live-search='true' class='select form-select personnelselect' id='select_" + nextindex + "' name='personnel[" + (nextindex - 1) + "][user_id]'><option value selected>--- Select Personnel ---</option></select><button class='btn btn-danger btn-sm remove my-3' id='remove_personnel_" + nextindex + "'type='button'><i class='fa fa-minus-circle'></i></button>");
    });

    $('#wrapper').on('click', '.remove', function () {
        var id = this.id;
        var split_id = id.split("_");
        var deleteindex = split_id[2];
        // // Remove <div> with id
        $("#userinlinemodel_" + deleteindex).remove();
    });


    let explosivelists = ['23gm TAG', '23gm TAG(BH)', '25gm TAG', '39gm TAG', '8gm TTP', 'BP Power Charge', 'Casing Cutter Charge', 'Tubing Cutter Charge', 'RTG Charge', 'SWC Charge', 'SPLIT Shot Charge'];
    let primachordlists = ['T-150', 'T-190', 'PT-185', 'PT-150', 'BP SECONDARY IGNITOR'];
    let detonatorlists = ['15FDE', '26FDE', '1015E', 'BP PRIMARY IGNITOR', 'CUTTER DETO 432'];

    $('#add_more_logs').click(function (event) {
        event.preventDefault();
        var lastid = $(".log-form:last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[1]) + 1;
        var explosiveoption= "";
        var primachordoption= "";
        var detonatoroption= "";
        for (let i = 0; i < explosivelists.length; i++) {
            explosiveoption += "<option value='" + explosivelists[i] + "'>" + explosivelists[i] + "</option>";
        }
        for (let i = 0; i < primachordlists.length; i++) {
            primachordoption += "<option value='" + primachordlists[i] + "'>" + primachordlists[i] + "</option>";
        }
        for (let i = 0; i < detonatorlists.length; i++) {
            detonatoroption += "<option value='" + detonatorlists[i] + "'>" + detonatorlists[i] + "</option>";
        }
        // Adding new div container after last occurance of element class
        $(".log-form:last").after("<div class='log-form' id='id-log-form_" + nextindex + "'><hr class='my-3' style='color:#000000; border-top:5px solid; opacity:0.5;'><h2 class='my-2'>Run - " + nextindex + "</h2></div>");
        // Adding element to <div>
        $("#id-log-form_" + nextindex).append(
            // Run No
            "<div id='div_logmodel_set-" + nextindex + "-runNo' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-runNo' class='form-label requiredField'>Run No.<span class='asteriskField'>*</span></label><input type='number' name='logrecorded[" + (nextindex - 1) + "][runNo]' class='numberinput form-control' id='id_logmodel_set-" + nextindex + "-runNo' value=" + nextindex + "></div>" +
            // Type of Logs Recorded
            "<div id='div_logmodel_set-" + nextindex + "-logRecorded' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-logRecorded' class='form-label requiredField'>Type of Logs Recorded<span class='asteriskField'>*</span> </label><select name='logrecorded[" + (nextindex - 1) + "][logRecorded]' class='textinput textInput form-control logsRecorded' id='id_logmodel_set-" + nextindex + "-logRecorded' required><option value='' selected disabled>--- Select Recorded Log ---</option><optgroup label='Cased Hole logs' id='CH'><optgroup label='Explosive logs' id='explosive-logs'><option value='Perforation(CCL)'>Perforation(CCL)</option><option value='TTP(CCL)'>TTP(CCL)</option><option value='Bridge Plug(CCL)'>Bridge Plug(CCL)</option><option value='Tubing Puncture'>Tubing Puncture</option><option value='Casing Cutter'>Casing Cutter</option><option value='Tubing Cutter'>Tubing Cutter</option><option value='Packer Setting'>Packer Setting</option></optgroup><optgroup label='Non-Explosive logs' id='non-explosive-logs'><option value='Junk Basket(CCL)'>Junk Basket(CCL)</option><option value='GR-CCL'>GR-CCL</option><option value='GR-TCL'>GR-TCL</option><option value='SBT-GR-CCL'>SBT-GR-CCL</option><option value='RBT-GR-CCL'>RBT-GR-CCL</option><option value='ULTEX-GR-CCL'>ULTEX-GR-CCL</option></optgroup></optgroup><optgroup label='Production Logs' id='PL'><option value='Production Log'>Production Log</option><option value='Temperature Log'>Temperature Log</option></optgroup><optgroup label='Open Hole Logs' id='OHL'><optgroup label='Non-Explosive logs' id='oh-non-explosive-logs'><option value='HDIL-ORIT-SP-GR'>HDIL-ORIT-SP-GR</option><option value='RTEX-ORIT-SP-GR'>RTEX-ORIT-SP-GR</option><option value='ZDEN-CN-GR'>ZDEN-CN-GR</option><option value='STAR-ORIT-GR'>STAR-ORIT-GR</option><option value='XMAC-ORIT-GR'>XMAC-ORIT-GR</option></optgroup><optgroup label='Explosive logs' id='oh-explosive-logs'><option value='SWC'>SWC</option></optgroup></optgroup><optgroup label='Explosive logs' id='other-explosive-logs'><option value=''>Other Explosive Logs</option></optgroup><optgroup label='Non-Explosive logs' id='other-non-explosive-logs'><option value=''>Other Non-Explosive Logs</option></optgroup></select></div>" +
            // Bottom Depth
            "<div id='div_logmodel_set-" + nextindex + "-bottomDepth' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-bottomDepth' class='form-label requiredField'>Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][bottomDepth]' step='any' class='numberinput form-control'id='id_logmodel_set-" + nextindex + "-bottomDepth'></div>" +
            // Top Depth
            "<div id='div_logmodel_set-" + nextindex + "-topDepth' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-topDepth' class='form-label requiredField'>Top Depth(m)<span class='asteriskField'>*</span> </label><input type='number' name='logrecorded[" + (nextindex - 1) + "][topDepth]' step='any' class='numberinput form-control' id='id_logmodel_set-" + nextindex + "-topDepth'></div>" +
            // Tool No
            "<div id='div_logmodel_set-" + nextindex + "-toolNo' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-toolNo' class='form-label'>Tool No.</label><input type='text' name='logrecorded[" + (nextindex - 1) + "][toolNo]' maxlength='255' class='textinput textInput form-control' id='id_logmodel_set-" + nextindex + "-toolNo'></div>" +
            // Log Quality
            "<div id='div_logmodel_set-" + nextindex + "-logQuality' class='mb-3'><label for='id_logmodel_set-" + nextindex + "-logQuality' class='form-label requiredField'>Log Quality<span class='asteriskField'>*</span> </label><input type='text' name='logrecorded[" + (nextindex - 1) + "][logQuality]' maxlength='20' class='textinput textInput form-control' id='id_logmodel_set-" + nextindex + "-logQuality'></div>" +
            // Explosive fields (hidden by default)
            "<div id='div_logmodel_set-" + nextindex + "-bottomShotDepth' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-bottomShotDepth' class='form-label'>Bottom Shot Depth(m)</label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][bottomShotDepth]' step='any'class='numberinput form-control' id='id_logmodel_set-" + nextindex + "-bottomShotDepth'></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-topShotDepth' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-topShotDepth' class='form-label'>Top Shot Depth(m)</label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][topShotDepth]' step='any'class='numberinput form-control' id='id_logmodel_set-" + nextindex + "-topShotDepth'></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-charge' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-charge' class='form-label'>Charge Type</label><select type='text' name='logrecorded[" + (nextindex - 1) + "][charge]' maxlength='20'class='textinput textInput form-control' id='id_logmodel_set-" + nextindex + "-charge'><option value='' >--- Select Charge type ---</option>" + explosiveoption + "<option value='NA'>NA</option></select></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-chargeNo' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-chargeNo' class='form-label'>Charge Qty.(Nos.)</label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][chargeNo]' class='numberinput form-control'id='id_logmodel_set-" + nextindex + "-chargeNo'></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-primaChord' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-primaChord' class='form-label'>Prima Chord Type</label> <select type='text' name='logrecorded[" + (nextindex - 1) + "][primaChord]' maxlength='20'class='textinput textInput form-control' id='id_logmodel_set-" + nextindex + "-primaChord'><option value=''>--- Select Prima Chord type ---</option>" + primachordoption + "<option value='NA'>NA</option></select></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-primaChordQty' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-primaChordQty' class='form-label'>P/C Length (m)</label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][primaChordQty]' step='any'class='numberinput form-control' id='id_logmodel_set-" + nextindex + "-primaChordQty'></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-fuse' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-fuse' class='form-label'>Fuse Type</label> <select type='text' name='logrecorded[" + (nextindex - 1) + "][fuse]' maxlength='20'class='textinput textInput form-control' id='id_logmodel_set-" + nextindex + "-fuse'><option value=''>--- Select Detonator type ---</option>" + detonatoroption + "<option value='NA'>NA</option></select></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-fuseNo' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-fuseNo' class='form-label'>Fuse Qty. (Nos.)</label> <input type='number' name='logrecorded[" + (nextindex - 1) + "][fuseNo]' class='numberinput form-control'id='id_logmodel_set-" + nextindex + "-fuseNo'></div>" +
            "<div id='div_logmodel_set-" + nextindex + "-fMf' class='mb-3 explosive-job d-none'><label for='id_logmodel_set-" + nextindex + "-fMf' class='form-label'>F/MF</label><select name='logrecorded[" + (nextindex - 1) + "][fMf]' class='select form-select' id='id_logmodel_set-" + nextindex + "-fMf'><option value='' >---------</option><option value='F'>F</option><option value='MF'>MF</option></select></div>" +
            // Remove button
            "<button class='btn btn-danger btn-sm remove my-3' id='remove_logs_" + nextindex + "'type='button'><i class='fa fa-minus-circle'></i></button>"
        );
    });

    $('#add_more_explosive').click(function (event) {
        event.preventDefault();
        var lastid = $(".explosive-form:last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[1]) + 1;
        var explosiveoption= "";
        var primachordoption= "";
        var detonatoroption= "";
        for (let i = 0; i < explosivelists.length; i++) {
            explosiveoption += "<option value='" + explosivelists[i] + "'>" + explosivelists[i] + "</option>";
        }
        for (let i = 0; i < primachordlists.length; i++) {
            primachordoption += "<option value='" + primachordlists[i] + "'>" + primachordlists[i] + "</option>";
        }
        for (let i = 0; i < detonatorlists.length; i++) {
            detonatoroption += "<option value='" + detonatorlists[i] + "'>" + detonatorlists[i] + "</option>";
        }
        var explosivesoption= explosiveoption + primachordoption + detonatoroption;

        // Adding new div container after last occurance of element class
        $(".explosive-form:last").after("<div class='explosive-form' id='id-explosive-form_" + nextindex + "'><hr class='my-3' style='color:#000000; border-top:5px solid; opacity:0.5;'><h2 class='my-3'>Explosive - " + nextindex + "</h2></div>");
        // Adding element to <div>
        $("#id-explosive-form_" + nextindex).append("<div id='div_id_explosive_set-" + nextindex + "-explosives' class='mb-3'><label for='id_explosive_set-" + nextindex + "-explosives' class='form-label'>Charges</label><select name='explosive[" + (nextindex - 1) + "][explosive]' type='text' placeholder='--- Select Charge type ---' class='select form-select' id='id_explosive_set-" + nextindex + "-explosives'><option value=''  >--- Select Charge type ---</option>" + explosivesoption + "<option value='NA' >NA</option></select></div><div id='div_id_explosive_set-" + nextindex + "-issued' class='mb-3'><label for='id_explosive_set-" + nextindex + "-issued' class='form-label'> Charge Issued</label><input type='text' name='explosive[" + (nextindex - 1) + "][issued]' placeholder='Issued' class='textinput textInput form-control' id='id_explosive_set-" + nextindex + "-issued' ></div><div id='div_id_explosive_set-" + nextindex + "-used' class='mb-3'><label for='id_explosive_set-" + nextindex + "-used' class='form-label'>Charge Used</label><input type='text' name='explosive[" + (nextindex - 1) + "][used]' placeholder='Used' class='textinput textInput form-control' id='id_explosive_set-" + nextindex + "-used'></div><div id='div_id_explosive_set-" + nextindex + "-returned' class='mb-3'><label for='id_explosive_set-" + nextindex + "-returned' class='form-label'>Charge Returned</label><input type='text' name='explosive[" + (nextindex - 1) + "][returned]' placeholder='Returned' class='textinput textInput form-control' id='id_explosive_set-" + nextindex + "-returned'></div><button class='btn btn-danger btn-sm remove my-3' id='remove_explosives_" + nextindex + "'type='button'><i class='fa fa-minus-circle'></i></button>");
    });

    $('.logs-wrapper').on('click', '.remove', function () {
        const deleteindex = this.id.split("_")[2];
        $("#id-log-form_" + deleteindex).remove();

        updateGlobalExplosiveSection();
        toggleSWCSection();
    });


    $('#div-explosive').addClass('d-none');
    var selectedOption = $(this).find('option:selected');
    if (selectedOption.closest('optgroup').attr('id') == 'explosive-logs') {
        $('.explosive-job').removeClass('d-none');
        $('#div-explosive').removeClass('d-none');
    }

    $('.logs-wrapper').on('change', '.logsRecorded', function () {
        const $run = $(this).closest('.log-form');
        const $explosiveFields = $run.find('.explosive-job');
        const selectedOption = $(this).find('option:selected');

        const isExplosive =
            selectedOption.closest('optgroup').attr('id') === 'explosive-logs' ||
            selectedOption.closest('optgroup').attr('id') === 'oh-explosive-logs' ||
            selectedOption.text() === 'Other Explosive Logs';

        if (isExplosive) {
            // ✅ show ONLY this run’s explosive fields
            $explosiveFields.removeClass('d-none');
        } else {
            // ✅ hide ONLY this run’s explosive fields
            $explosiveFields.addClass('d-none');
        }

        // 🔑 After fixing this run, re-check global state
        updateGlobalExplosiveSection();
        toggleSWCSection();
    });


    $('.explosive-wrapper').on('click', '.remove', function () {
        var id = this.id;
        var split_id = id.split("_");
        var deleteindex = split_id[2];
        // // Remove <div> with id
        $("#id-explosive-form_" + deleteindex).remove();
    });

    $("#id_unitNo").change(function () {
        $.ajax({
            type: "GET",
            url: "ajaxcalls/jobno",
            data: {
                'unitNo': $(this).val()
            },
            success: function (response) {
                //Perform actions with the response data from the view
                $("#id_jobNo").attr('value', response);
                // $("#id_jobNo").attr('t-att-value', data.jobNo)
                $("#id_jobNo").value = response;
            }
        });
    });

    $("#secondstep").click(function () {
        if ($("#id_logType").val() === "CH") {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_viscosity,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').hide();
        }
        else if ($("#id_logType").val() === "PL") {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_viscosity,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').hide();
        }
        else {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_spgr,#div_id_viscosity,#div_id_mudType,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').show();
        }
    });
    $("#first-step").click(function () {
        if ($("#id_logType").val() === "CH") {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_viscosity,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').hide();
        }
        else if ($("#id_logType").val() === "PL") {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_viscosity,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').hide();
        }
        else {
            $('#div_id_rm,#div_id_rmtemp,#div_id_rmf,#div_id_rmftemp,#div_id_rmc,#div_id_rmctemp,#div_id_bht,#div_id_bhtdepth,#div_id_spgr,#div_id_viscosity,#div_id_mudType,#div_id_waterloss,#div_id_ph,#div_id_oilpercnt,#div_id_kcl_barytes,#div_id_salinity,#div_id_lastcirc_from, #div_id_lastcirc_to').show();
        }
    });

    $("#id_cableSize").change(function () {
        $.ajax({
            url: "ajaxcalls/cableinfo",
            data: {
                'unitNo': $("#id_unitNo").val(),
                'cableSize': $("#id_cableSize").val(),
            },
            dataType: 'json',
            success: function (cabledata) {
                if (Object.keys(cabledata).length > 0) {
                    //Perform actions with the response data from the view
                    var date = new Date(cabledata.shoeDate); // Or your desired Date object
                    let day = date.getDate();
                    let month = date.getMonth() + 1; // getMonth() returns 0-indexed month
                    let year = date.getFullYear();

                    // Add leading zeros if day or month is a single digit
                    if (day < 10) {
                        day = '0' + day;
                    }
                    if (month < 10) {
                        month = '0' + month;
                    }

                    const formattedDate = `${year}-${month}-${day}`;
                    $("#id_shoeDate").attr('value', formattedDate);
                    $("#id_weakPoint").attr('value', cabledata.weakPoint);
                    $("#id_cableLength").attr('value', cabledata.cableLength);
                    $("#id_initialLength").attr('value', cabledata.initialLength);
                    if ($("#id_cableSize").val() === '15/32') {
                        $('#id_cableHeadSize').val('3 3/8');
                    }
                    else {
                        $('#id_cableHeadSize').val('1 7/16');
                    }
                }
                else {
                    //Perform default actions with the response data from the view
                    $("#id_shoeDate").removeAttr('value');
                    $("#id_weakPoint").removeAttr('value');
                    $("#id_cableLength").removeAttr('value');
                    $("#id_initialLength").removeAttr('value');
                    $('#id_cableHeadSize').prop('selectedIndex', 0);
                    alert("Cable data not found !!!");
                }
            },
            error: function () {
                alert("Cable data not found !!!");
            }
        });
    });

    $("#thirdstep").click(function () {
        if ($("#id_logType").val() === "PL") {
            $('#div-explosive').addClass('d-none');
        }
    }
    );
    $("#second-step").click(function () {
        if ($("#id_logType").val() === "PL") {
            $('#div-explosive').addClass('d-none');
        }
    }
    );

    $(".explosive-wrapper").on("click keyup keydown", function () {
        var total = $('.explosive-form').length;
        for (var i = 1; i <= total; i++) {
            issueid = "#id_explosive_set-" + (i) + "-issued";
            usedid = "#id_explosive_set-" + (i) + "-used";
            returnid = "#id_explosive_set-" + (i) + "-returned";
            $(returnid).val($(issueid).val() - $(usedid).val());
        }
    });

    $('#div_id_nearMissDesc').hide();
    $("#id_nearMiss").change(function () {
        if ($(this).val() == 1) {
            $('#div_id_nearMissDesc').show();
        } else {
            $('#div_id_nearMissDesc').hide();
        }
    });

    $("#div_id_elecLockout, #div_id_elecLockoutNo").hide();
    $("#id_permitType").change(function () {
        if ($(this).val() === "Hot Work Permit") {
            $("#div_id_elecLockout").show();
        } else {
            $("#div_id_elecLockout, #div_id_elecLockoutNo").hide();
        }
    });

    $("#div_id_elecLockoutNo").hide();
    $("#id_elecLockout").change(function () {
        if ($(this).val() === "1") {
            $("#div_id_elecLockoutNo").show();
        } else {
            $("#div_id_elecLockoutNo").hide();
        }
    });

    $('#div_id_objective, #div_id_observations').hide();
    $("#third-step").click(function () {
        if ($("#id_logType").val() === "PL") {
            $('#div_id_objective, #div_id_observations').show();
        }
        else {
            $('#div_id_objective, #div_id_observations').hide();
        }
    });
    $("#finalstep").click(function () {
        if ($("#id_logType").val() === "PL") {
            $('#div_id_objective, #div_id_observations').show();
        }
        else {
            $('#div_id_objective, #div_id_observations').hide();
        }
    });

    function updateGlobalExplosiveSection() {
        let hasExplosiveRun = false;

        $('.logsRecorded').each(function () {
            const selectedOption = $(this).find('option:selected');

            if (
                selectedOption.closest('optgroup').attr('id') === 'explosive-logs' ||
                selectedOption.closest('optgroup').attr('id') === 'oh-explosive-logs' ||
                selectedOption.text() === 'Other Explosive Logs'
            ) {
                hasExplosiveRun = true;
                return false; // break loop
            }
        });

        if (hasExplosiveRun) {
            $('#div-explosive').removeClass('d-none');
        } else {
            $('#div-explosive').addClass('d-none');
        }
    }

    function toggleSWCSection() {
        const logType = $('#id_logType').val();
        let hasSWC = false;

        $('.logsRecorded').each(function () {
            if ($(this).val() === 'SWC') {
                hasSWC = true;
                return false; // break loop
            }
        });

        if (logType === 'OH' && hasSWC) {
            $('#div-swc').show();
        } else {
            $('#div-swc').hide();
        }
    }

    $('#id_logType').on('change', function () {
        toggleSWCSection();
    });

    $('.logsRecorded').each(function () {
        $(this).trigger('change');
    });

    toggleSWCSection();

});

