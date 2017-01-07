// rev 20160106-2109
var maintenance = [];
var part = [];
var qid = undefined;
var invoice = [];

function getUrlVars() {
    var vars = []
        , hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function formatMoney(n, c, d, t) {
    //var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "." : d, t = t == undefined ? "," : t, s = n < 0 ? "-" : "", i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function rederTableInvoice(){
    var objLen = invoice.length;
    // prepare row
    var appendRow = "";
    for (var i = 0; i < objLen; i++) {
        // col 1
        appendRow += '<tr id="' + i + '"><td style="text-align: Center;">' + (i+1) + '</td>';
        appendRow += '<td>' + invoice[i].list + '</td>';
        appendRow += '<td style="text-align: right;">' + formatMoney( invoice[i].price, 2) + '</td>';
        appendRow += '<td>\
                        <button id="btn-invoice-edit" data-i="' + i + '" data-toggle="modal" data-target="#edit-invoice-description" class="btn btn-default btn-xs">\
                            <span class="glyphicon glyphicon-pencil"></span></button>\
                        <button id="btn-invoice-remove" class="btn btn-default btn-xs" onclick="removeInvoiceDescription(' + i + ')">\
                            <span class="glyphicon glyphicon-remove"></span></button>\
                      </td>';
        appendRow += '</tr>';
    }
    // clear table body content
    $("table#tableInvoice > tbody").html("");

    // append row
    $("table#tableInvoice > tbody").html(appendRow);
}

function removeInvoiceDescription(i){
    var r = confirm("คุณต้องการที่จะลบรายการที่ " + (i+1) + " หรือไม่?");

    if(r){
        invoice.splice(i, 1); // remove index = i, count = 1

        // render table
        rederTableInvoice();
        calTotalInvoice();
    }

}

function calTotalInvoice() {
    var total_invoice = 0;
    for (var i = 0, len = invoice.length; i < len; i++) {
        total_invoice += parseFloat(invoice[i].price);
    }
    // update DOM
    var total = total_invoice.toFixed(2);
    $("#invoice-total").text(formatMoney(total_invoice, 2));
    var vat = total_invoice * 0.07;
    $("#invoice-tax").text(formatMoney(vat));
    var grandTotal = total_invoice + vat;
    $("#invoice-grand-total").text(formatMoney(grandTotal));
};

function updateInvoiceDescription( i ){
    //console.log(i);
    var modal = $('#edit-invoice-description');
    invoice[i].list = modal.find('.modal-body input#list').val();
    invoice[i].price = modal.find('.modal-body input#price').val();

    //console.log(invoice);
    rederTableInvoice();
    calTotalInvoice();
    $("#edit-invoice-description").modal('hide');
}


function updateMaintenanceDescription( i ){
    //console.log(i);
    var modal = $('#edit-maintenance-description');
    maintenance[i].list = modal.find('.modal-body input#list').val();
    maintenance[i].price = modal.find('.modal-body input#price').val();

    //console.log(invoice);
    renderTableBody();
    calTotal();
    $("#edit-maintenance-description").modal('hide');
}
function updatePartDescription( i ){
    //console.log(i);
    var modal = $('#edit-part-description');
    part[i].list = modal.find('.modal-body input#list').val();
    part[i].price = modal.find('.modal-body input#price').val();

    //console.log(invoice);
    renderTableBody();
    calTotal();
    $("#edit-part-description").modal('hide');
}
function renderTableBody() {
        /* append row when plus button clicked  */
        /* update 20161019: render table based on presented objects */
        // find len
        var objLen = maintenance.length;
        if (part.length > maintenance.length) objLen = part.length;
        // prepare row
        var appendRow = "";
        for (var i = 0; i < objLen; i++) {
            // col 1
            appendRow += '<tr id="' + i + '"><td style="text-align: Center;">' + (i+1) + '</td>';
            if (typeof maintenance[i] !== 'undefined') {
                // col 2
                appendRow += '<td>' + maintenance[i].list + '</td>\
                    <td>' + maintenance[i].price + '</td>';
                // col 3
                appendRow += '<td>\
                    <button id="maintenance-update" data-i="' + i + '" data-toggle="modal" data-target="#edit-maintenance-description" class="btn btn-default btn-xs"> \
                        <span class="glyphicon glyphicon-pencil"></span> \
                    </button>\
                    <button id="maintenance-del" class="btn btn-default btn-xs"> \
                        <span class="glyphicon glyphicon-remove"></span> \
                    </button></td>';
            }
            else {
                appendRow += '<td></td><td></td>'
                appendRow += '<td></td>'
            }
            if (typeof part[i] !== 'undefined') {
                appendRow += '<td>' + part[i].list + '</td>\
                    <td>' + part[i].price + '</td>';
                appendRow += '<td>\
                    <button id="part-update" data-i="' + i + '" data-toggle="modal" data-target="#edit-part-description" class="btn btn-default btn-xs"> \
                        <span class="glyphicon glyphicon-pencil"></span> \
                    </button>\
                    <button id="part-del" class="btn btn-default btn-xs"> \
                        <span class="glyphicon glyphicon-remove"></span> \
                    </button></td>';
            }
            else {
                appendRow += '<td></td><td></td>'
                appendRow += '<td></td>'
            }
            appendRow += '</tr>';
        }
        // clear table body content
        $("table#myTable > tbody").html("");
        // append row
        $("table#myTable > tbody").html(appendRow);
    }

    function updateTableIndex() {
        var row = $("#myTable tbody > tr");
        for (var i = 0, nRow = row.size(); i < nRow; i++) {
            // select first column
            var col = $(row).eq(i).find("td");
            // update text
            $(col).eq(0).text(i + 1);
        }
    }

    function enterDescription() {
        // check, empty?
        ///////////////////////////////////////////////////////////////////////////////
        if ($("#maintenance-list").val() == "" && $("#part-list").val() == "") {
            alert("กรุณาป้อนรายการ");
            return false;
        }
        // check, empty?
        if ($("#maintenance-list").val() != "" && $("#maintenance-price").val() === "") { // งงมาก  $("#maintainance-price").val() เป็น 'undefined' แต่บรรทัดนี้เป็น ''
            alert("กรุณาป้อนราคาซ่อม");
            return false;
        }
        // check, empty?
        if ($("#part-list").val() != "" && $("#part-price").val() === "") { // งงมาก  $("#maintainance-price").val() เป็น 'undefined' แต่บรรทัดนี้เป็น ''
            alert("กรุณาป้อนราคาอะไหล่");
            return false;
        }
        // Push data into object.
        ///////////////////////////////////////////////////////////////////////////////
        if ($("#maintenance-list").val() != "") {
            maintenance.push({
                list: $("#maintenance-list").val(), price: $("#maintenance-price").val()
            });
        }
        if ($("#part-list").val() != "") {
            part.push({
                list: $("#part-list").val(), price: $("#part-price").val()
            });
        }

        ////////////////////////////////////////////////////////////////////////////////
        renderTableBody();
        /* clear text box value */
        $("#maintenance-list").val("");
        $("#maintenance-price").val("");
        $("#part-list").val("");
        $("#part-price").val("");

        /* calulate total */
        calTotal();
        updateTableIndex();

        /* Increment ID */
//        id++;
    }

function calTotal() {
        var total_maintenance = 0;
        var total_part = 0;
        for (var i = 0, len = maintenance.length; i < len; i++) {
            total_maintenance += parseFloat(maintenance[i].price);
        }
        for (var i = 0, len = part.length; i < len; i++) {
            total_part += parseFloat(part[i].price);
        }
        var total = total_maintenance + total_part;
        // update DOM
        $("#maintenance-total").text(formatMoney(total_maintenance, 2));
        $("#part-total").text(formatMoney(total_part, 2)); // toFixed - number of digit
        $("#total").text(formatMoney(total, 2));
    }

$('body').on("click", "#btn-search", function(){
    $.get("index.php?r=invoice/customer-search",{
        type: $("#customer-type option:selected").val(),
        fullname: $("#fullname").val()
    }, function(data){
        $("#result").html("");
        $("#result").html( data );
    });
});



$(document).ready(function () {
    
    $("#multiple-claim-no").select2();
    
//    var maintenance = globalMaintenance;
//    var part = globaPart;
//    var qid = globalQid;
//    var invoice = globalInvoice;
    var id = 1;
    var list = [];
    ///////////////////////////////////////////////////////
    /* Initial calculation for edit page */

    /* Quotation */
    renderTableBody();
    calTotal();
    updateTableIndex();

    /* Invoice
    - 20161112 if remove, edit invoice won't load'*/ 
    rederTableInvoice();
    calTotalInvoice();

    ///////////////////////////////////////////////////////
    // update 20160106 - make total field editable
    $.fn.enterKey = function (fnc) {
        return this.each(function () {
            $(this).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    fnc.call(this, ev);
                }
            })
        })
    }

    $("#maintenance-total").dblclick( function() {
        $("#maintenance-total-editing input").val( $(this).text() );
        $("#maintenance-total-editing").show();
        $(this).hide();
    });
    $("#maintenance-total-editing input").enterKey( function() {
        $("#maintenance-total").text( $(this).val() );
        $("#maintenance-total").show();
        $("#maintenance-total-editing").hide();
    });   

    $("#part-total").dblclick( function() {
        $("#part-total-editing input").val( $(this).text() );
        $("#part-total-editing").show();
        $(this).hide();
    });
    $("#part-total-editing input").enterKey( function() {
        $("#part-total").text( $(this).val() );
        $("#part-total").show();
        $("#part-total-editing").hide();
    });    

    $("#total").dblclick( function() {
        $("#total-editing input").val( $(this).text() );
        $("#total-editing").show();
        $(this).hide();
    });
    $("#total-editing input").enterKey( function() {
        $("#total").text( $(this).val() );
        $("#total").show();
        $("#total-editing").hide();
    });
    ////////////////////////////////////////////////////////////
    // update 20170106 - invoice total editable
    $("#invoice-total").dblclick( function() {
        $("#invoice-total-editing input").val( $(this).text() );
        $("#invoice-total-editing").show();
        $(this).hide();
    });
    $("#invoice-total-editing input").enterKey( function() {
        $("#invoice-total").text( $(this).val() );
        $("#invoice-total").show();
        $("#invoice-total-editing").hide();
    });

    $("#invoice-tax").dblclick( function() {
        $("#invoice-tax-editing input").val( $(this).text() );
        $("#invoice-tax-editing").show();
        $(this).hide();
    });
    $("#invoice-tax-editing input").enterKey( function() {
        $("#invoice-tax").text( $(this).val() );
        $("#invoice-tax").show();
        $("#invoice-tax-editing").hide();
    });

    $("#invoice-grand-total").dblclick( function() {
        $("#invoice-grand-total-editing input").val( $(this).text() );
        $("#invoice-grand-total-editing").show();
        $(this).hide();
    });
    $("#invoice-grand-total-editing input").enterKey( function() {
        $("#invoice-grand-total").text( $(this).val() );
        $("#invoice-grand-total").show();
        $("#invoice-grand-total-editing").hide();
    });

    
    ///////////////////////////////////////////////////////

    /* Bind enter key to function */
    $("#maintenance-list").bind('keypress', function (e) {
        if( $("#plate-no").val() === null)
            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
                enterDescription();
        }
    });
    $("#maintenance-price").bind('keypress', function (e) {
        if( $("#plate-no").val() === null)
            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#part-list").bind('keypress', function (e) {
        if( $("#plate-no").val() === null)
            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#part-price").bind('keypress', function (e) {
        if( $("#plate-no").val() === null)
            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#maintenance-add").click(function () {
        enterDescription();
    });
    $("#part-add").click(function () {
        enterDescription();
    });


    $("table#myTable").on("click", "#maintenance-del", function (event) {
        var closestRow = $(this).closest("tr");
        removeIndex = closestRow[0].id;
        maintenance.splice(removeIndex, 1);
        // re-render body table
        renderTableBody();
        // calulate total
        calTotal();
        updateTableIndex();
    });
    $("table#myTable").on("click", "#part-del", function (event) {
        var closestRow = $(this).closest("tr");
        removeIndex = closestRow[0].id;
        part.splice(removeIndex, 1);
        // re-render body table
        renderTableBody();
        // calulate total
        calTotal();
        updateTableIndex();
    });

    function arrayObjectIndexOf(myArray, searchTerm, property) {
        for (var i = 0, len = myArray.length; i < len; i++) {
            if (myArray[i][property] === parseInt(searchTerm)) {
                return i;
            }
        }
        return -1;
    }




    $("#maintenance-list").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "index.php?r=description/description-list"
                , dataType: "json"
                , data: {
                    term: request.term,
                    vid: $("#plate-no").val()
                }
                , success: function (data) {
                    //console.log(data);
                    list = data;
                    if (data.length != 0) response(data);
                    else response({
                        value: "ไม่พบ"
                    });
                }
            });
        }
        , close: function (event, ui) {
            var selectedText = $("#maintenance-list").val();
            // find selected price
            var index = -1;
            for (var i = 0, len = list.length; i < len; i++) {
                if (list[i]["value"] === selectedText) {
                    index = i;
                }
            }
            $("#maintenance-price").val(list[index].price);
        }
    });
    $("#part-list").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "index.php?r=description/description-list"
                , dataType: "json"
                , data: {
                    term: request.term,
                    vid: $("#plate-no").val()
                }
                , success: function (data) {
                    list = data;
                    if (data.length != 0) response(data);
                    else response({
                        value: "ไม่พบ"
                    });
                }
            });
        }
        , close: function (event, ui) {
            var selectedText = $("#part-list").val();
            // find selected price
            var index = -1;
            for (var i = 0, len = list.length; i < len; i++) {
                if (list[i]["value"] === selectedText) {
                    index = i;
                }
            }
            $("#part-price").val(list[index].price);
        }
    });
    $("#btn-save").on("click", function (event, ui) {
        // get quotation info
        quotation_info = {
            CID: $("#customer option:selected").val(), 
            customerType: $("#customer-type:checked").val(), 
            claimNo: $("#claim-no").val(), 
            damageLevel: $("#damage-level").val(), 
            damagePosition: $("#damage-position").val(), 
            VID: $("#plate-no option:selected").val()            
        };
        var totalManual = {
            maintenance: $("#maintenance-total-editing input").val(),
            part: $("#part-total-editing input").val(),
            total: $("#total-editing input").val()
        }
        if(totalManual.maintenance !== "" && !$.isNumeric(totalManual.maintenance)){
            alert('รวมรายการซ่อมไม่ถูกต้อง!');
            return false;
        }
        if(totalManual.part !== "" && !$.isNumeric(totalManual.part)){
            alert('รวมรายการอะไหล่ไม่ถูกต้อง!');
            return false;
        }
        if(totalManual.total !== "" && !$.isNumeric(totalManual.total)){
            alert('รวมไม่ถูกต้อง!');
            return false;
        }
        // check empty
        if (quotation_info.vieclePlateNo == "") {
            alert("กรุณาข้อมูลรถยนต์");
            return false;
        }
        if (quotation_info.claimNo == "") {
            alert("กรุณาใส่หมายเลขเคลม");
            return false;
        }
        if (maintenance.length == 0 && part.length == 0) {
            alert("กรุณาเพิ่มรายการ");
            return false;
        }
        
        
        /* Send information to server */
        $.post("index.php?r=quotation/quotation-save",{
            quotation_info: 
                quotation_info, 
                maintenance_list: maintenance, 
                part_list: part, 
                totalManual : {
                    maintenance: $("#maintenance-total-editing input").val(),
                    part: $("#part-total-editing input").val(),
                    total: $("#total-editing input").val()
                }
        }, function(data) {
            console.log(data);
            // confirmation dialog
            var r = confirm(data.message);
            if (r === true && data.status === true) { // press OK
                // print quotation
                window.open(
                  '?r=quotation/report&qid=' + data.QID,
                  '_blank' // <- This is what makes it open in a new window.
                );
            }
            // redirect
            //window.location.replace("?r=quotation/view&qid=" + data.QID);
        });
    });
    /* edit description, save button clicked */
    $("#btn-edit-save").click(function () {
        $.ajax({
            method: "POST",
            url: "index.php?r=description/update", 
            type: 'json', 
            data: {
                //quotation_info: quotation_info, 
                qid: getUrlVars()['qid'], 
                maintenance_list: maintenance, 
                part_list: part,
                totalManual : {
                    maintenance: $("#maintenance-total-editing input").val(),
                    part: $("#part-total-editing input").val(),
                    total: $("#total-editing input").val()
                }
            }
            , success: function (data) {
                // confirmation dialog
                var r = confirm("บันทึกเรียบร้อย คุณต้องการสร้างเป็นการแก้ไข (revise) ใหม่หรือไม่");
                if (r == true) { // press OK
                    // update revised
                    $.get("?r=quotation/revised-up", {
                        QID: getUrlVars()['qid'], up: true
                    }, function(data){
                        window.location.replace("?r=quotation/view&qid=" + getUrlVars()['qid']);
                    });
                }
                else{
                    $.get("?r=quotation/revised-up", {
                        QID: getUrlVars()['qid'], up: false
                    }, function(data){
                        window.location.replace("?r=quotation/view&qid=" + getUrlVars()['qid']);
                    });
                }
            }
        });
    });
    $('#modal-save').on('hidden.bs.modal', function (e) {
        window.location.replace("index.php/?r=quotation/view&quotation_id=" + data.quotation_id);
    });
    $("#viewQuotation").click(function () {
        var id = $("#quotationId").val()
        window.location.replace("?r=quotation/view&quotation_id=" + id);
    });
    $("#quotationId").keyup(function () {
        $("#viewQuotation").removeClass('disabled');
    });
    $("#quotation-claim_no").keyup(function () {
        $("#btn-save").removeClass('disabled');
    });

    
    $("#btn-print").click(function () {
        var qid = getUrlVars()["qid"];
        var dateIndex = $("#history-date").val();
        window.open(
          '?r=quotation/report&qid=' + qid + '&dateIndex=' + dateIndex,
          '_blank' // <- This is what makes it open in a new window.
        );
    });
//    $("#history-date").change( function(){
//        //console.log( $("#history-date option:selected").text() ) ;
//        var tableUpdate =   $.post('?r=description/desc-history&date=' + $("#history-date option:selected").val(), function(){
//            //console.log("success");
//            $("#table-edit->tbody").html("");
//        });
//    });
    
//    $("#customer-type").change( function(){
//        console.log("test");
//    });
    $("#auto-generate").click( function(){
        /* Select plate id first */
        if( $("#plate-no").val() == null ){
            alert("ต้องเลือกข้อมูลรถก่อน");
        }

        /* Empty object */
        while(maintenance.length > 0)
            maintenance.pop();
        
        while(part.length > 0)
            part.pop();
        
        /* Get description */
        $.get("index.php/?r=description/auto-generated", {
            vid: $("#plate-no").val(), 
            damageLevel: $("#damage-level:checked").val(), 
            damagePos: $("#damage-position").val()
        }, function( data ){
            //console.log( data.MAINTENANCE );
            /* Push data and render table*/
            for(var key in data.MAINTENANCE){
                var obj = data.MAINTENANCE[key];
                maintenance.push({ list: obj.description, price: obj.price })
            }
            
            for(var key in data.PART){
                var obj = data.PART[key];
                part.push({ list: obj.description, price: obj.price })
            }
                renderTableBody();
                calTotal();
                updateTableIndex();
                
        } );
    });

    $('#edit-maintenance-description').on('show.bs.modal', function(event){
        //console.log(invoice);
        var button = $(event.relatedTarget);
        var i = button.data('i');

        var modal = $(this);
        modal.find('.modal-body input#list').val( maintenance[i].list );
        modal.find('.modal-body input#price').val( maintenance[i].price );
        modal.find('.modal-footer button#desc-update').attr("onclick", "updateMaintenanceDescription(" + i + ")");
    });
    $('#edit-part-description').on('show.bs.modal', function(event){
        //console.log(invoice);
        var button = $(event.relatedTarget);
        var i = button.data('i');

        var modal = $(this);
        modal.find('.modal-body input#list').val( part[i].list );
        modal.find('.modal-body input#price').val( part[i].price );
        modal.find('.modal-footer button#desc-update').attr("onclick", "updatePartDescription(" + i + ")");
    });
/////////////////////////////////////////////////////////////////////////////
////////////// Invoice //////////////////////////////////////////////////////


    $('#edit-invoice-description').on('show.bs.modal', function(event){
        //console.log(invoice);
        var button = $(event.relatedTarget);
        var i = button.data('i');

        var modal = $(this);
        modal.find('.modal-body input#list').val( invoice[i].list );
        modal.find('.modal-body input#price').val( invoice[i].price );
        modal.find('.modal-footer button#desc-update').attr("onclick", "updateInvoiceDescription(" + i + ")");
    });

    function enterInvoiceDescription(){
        invoice.push({
            list: $("#invoice-list").val(),
            price: $("#invoice-price").val()
        });

        /* Render table body */
        rederTableInvoice();

        // Cal total
        calTotalInvoice();

        /* Clear */
        $("#invoice-list").val("");
        $("#invoice-price").val("");
    }

    /* Bind enter key to function */
    $("#invoice-list").bind('keypress', function (e) {
//        if( $("#plate-no").val() === null)
//            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
                enterInvoiceDescription();
        }
    });
    /* Bind enter key to function */
    $("#invoice-price").bind('keypress', function (e) {
//        if( $("#plate-no").val() === null)
//            alert("กรุณาเลือกทะเบียนรถ");

        var code = e.keyCode || e.which;
        if (code == 13) {
                enterInvoiceDescription();
        }
    });

$("#btn-add-invoice").click(function () {
   enterInvoiceDescription();
});
$("table#myTable").on("click", "#btn-del-invoice", function (event) {
    var closestRow = $(this).closest("tr");
    // remove row
    closestRow.remove();
    // calulate total
    updateTableIndex();
});


    $("#maintenance-list").keyup(function () {
    $("#btn-save-invoice").removeClass('disabled');
});
$("#btn-save-invoice").on("click", function (event, ui) {
    $.post("index.php?r=invoice/create", {
        CID: $("#customer-list option:selected").val(),
        VID: $("#plate-no").val(),
        claim_no: $("#claim-no").val(),
        invoice: invoice,
        totalManual: {
            total: $("#invoice-total-editing input").val(),
            total_tax: $("#invoice-tax-editing input").val(),
            grandTotal: $("#invoice-grand-total-editing input").val(),
        }
    }, function(data){
        if( data.status ){
            var r = confirm("บันทึกเรียบร้อย");
            if(r){
                // print
                 window.open(
                      '?r=invoice/report&iid=' + data.IID,
                      '_blank' // <- This is what makes it open in a new window.
                );
            }

            window.location.replace("?r=invoice/view&iid=" + data.IID);
        }
    });
});
$("#btn-save-invoice-general").on("click", function (event, ui) {
    $.post("index.php?r=invoice-general/create", {
        CID: $("#customer-list option:selected").val(),
        VID: $("#plate-no").val(),
        claim_no: $("#claim-no").val(),
        invoice: invoice
    }, function(data){
        if( data.status ){
            var r = confirm("บันทึกเรียบร้อย\r\nคุณต้องการพิมพ์ใบแจ้งหนี้เลยหรือไม่");

            if(r){
                // print
                 window.open(
                      '?r=invoice/report&iid=' + data.IID,
                      '_blank' // <- This is what makes it open in a new window.
                );
            }
            window.location.replace("?r=invoice/view&iid=" + data.IID);
        }
    });
});
$("#btn-edit-invoice").click(function(){
    var iid = getUrlVars()["iid"];
    $.post("index.php?r=invoice/edit&iid=" + iid, {
        invoice: invoice,
        totalManual: {
            total: $("#invoice-total-editing input").val(),
            total_tax: $("#invoice-tax-editing input").val(),
            grandTotal: $("#invoice-grand-total-editing input").val(),
        }
    }, function(data){
        if( data.status ){
            var r = confirm("บันทึกเรียบร้อย\r\nคุณต้องการพิมพ์ใบแจ้งหนี้เลยหรือไม่");

            if(r){
                // print
                 window.open(
                      '?r=invoice/report&iid=' + data.IID,
                      '_blank' // <- This is what makes it open in a new window.
                );
            }
            window.location.replace("?r=invoice/view&iid=" + data.IID);
        }
    });
});
$("#invoiceId").keyup(function () {
    $("#viewInovoice").removeClass('disabled');
});
$("#viewInovoice").click(function () {
    window.location.replace("?r=invoice/view&invoice_id=" + $("#invoiceId").val());
});
$("#customer").select2();

$("input#customer-type").on('change', function(){
    
    if( this.value == "INSURANCE_COMP"){
        invoice.push({
            list: "ค่าซ่อมทำสีรถยนต์",
            price: $("#invoice-price").val()
        });
        // render table
        rederTableInvoice();
        calTotalInvoice();
    }
    else{
        invoice.pop();
        // render table
        rederTableInvoice();
        calTotalInvoice();
    }
    

    $("#address").html("");
    $("#tax-id").val("");
    $.get("?r=customer/customer-list", {customerType: this.value} , function(data){
        $("#customer-list").html("");
        $("#customer-list").html(data);
    })
});
$("#btn-print-invoice").click(function () {
    var iid = getUrlVars()["iid"];
    var dateIndex = $("#history-date").val();
    var hrefStr = "index.php?r=invoice/report&iid=" + iid + "&dateIndex=" + dateIndex;

    $("#btn-print-invoice").attr("href", hrefStr);
});
$("#btn-print-receipt").click( function(){
    var r = confirm("คุณต้องการพิมพ์ใบเสร็จใช่หรือไม่")

    var iid = getUrlVars()["iid"];
    var dateIndex = $("#history-date").val();
    var str = "index.php?r=receipt/report&iid=" + iid + "&dateIndex=" + dateIndex;
    if(r){
        window.open(
          str,
          '_blank' // <- This is what makes it open in a new window.
        );
    }
});

/////////////////////////////////////////////////////////
$("#photo-claim-no").select2();
$("#photo-claim-no").change( function(){
    window.location.replace("?r=photo/index&CLID=" + $("#photo-claim-no").val() );
});

$("#type").change( function(){
    $.get("?r=photo/detail", {
        CLID: $("#photo-claim-no option:selected").val(),
        type: $("#type option:selected").val() }, function(data){
            $("#result").html("");
            $("#result").html(data);
    });
});
$("#photo-print-current-page").click( function(){
    window.open(
          "?r=photo/report&CLID=" + $("#photo-claim-no option:selected").val() + "&type=" + $("#type option:selected").val(),
          '_blank' // <- This is what makes it open in a new window.
        );
});
$("#photo-print-all").click( function(){
    window.open(
          "?r=photo/report&CLID=" + $("#photo-claim-no option:selected").val(),
          '_blank' // <- This is what makes it open in a new window.
        );
}); 
$("#filter-viecle-name").change(function(){
//    console.log( $(this).val() );
    $.get("?r=config/viecle-model", {
        viecleName: $(this).val()
    }, function(data){
        $("#viecle-model-result").html(data);
    });
});
});

$(".modal-update").click( function(){
    $.get("?r=config/view-insurance",{
        CID: $(this).closest('tr').data('key')
    }, function(data){
        $("#update-insurance .modal-content").html(data);
        $("#update-insurance").modal();
    });
});

$(".modal-employee-update").click( function(){
     $.get("?r=config/view-employee",{
        EID: $(this).closest('tr').data('key')
    }, function(data){
        $("#modal-employee-update .modal-content").html(data);
        $("#modal-employee-update").modal();
    });
});

    //$(".update-viecle").click( function(){
    //    $.get("?r=viecle/view",{ 
    //        VID: $(this).closest('tr').data('key') 
    //    }, function(data){
    //        $("#update-viecle .modal-content").html(data);
    //        $("#update-viecle").modal();
    //    });
    //});


$("#add-claim-no").click( function(){
    $.get("?r=claim/view",{
        CLID:$("#multiple-claim-no").val()
    }, function(data){
        console.log( data );
        $("#selected-claim").append('<option value="' + data.CLID + '">' + data.claim_no + '</option>');
    })
});

$("#remove-selected-claim").click(function(){
    $("#selected-claim option:selected").remove();
});

$("#insurance-company").change( function(){
    $.get("?r=customer/view",{ 
        CID: $(this).val()
    }, function(data){
        console.log( data );
        $("#address").html( data.address );
        $("#tax-id").val( data.taxpayer_id)
    });
});

$("#btn-save-multiple").click(function(){
    var claims = [];
    $("#selected-claim option").each( function(){
        claims.push($(this).val());
    });
    console.log(claims);
    
    $.post("?r=receipt/create-multiple",{
        CID: $("#insurance-company option:selected").val(),
        claims: claims,
        invoice: invoice
    }, function(data){
        if(data.status){
            alert('เรียบร้อย');
            window.open(
                "index.php?r=receipt/report&iid=" + data.iid,
                '_blank' // <- This is what makes it open in a new window.
            );
            window.location.replace("?r=receipt/view-multiple-claim&rid=" + data.rid);
        }
        else{
            alert('ล้มเหลว\r\n' + data);
        }
        
    });
});

$("#update-multiple-claim").click(function(){
    var rid = getUrlVars()["rid"];
    $.post("?r=receipt/update-multiple-claim&rid=" + rid, {
        invoice: invoice
    }, function(data){
        if( data ){
            var r = confirm("บันทึกเรียบร้อย");
            if(r){
                // print
                 window.open(
                      '?r=receipt/report&iid=' + data.IID,
                      '_blank' // <- This is what makes it open in a new window.
                );
            }
            window.location.replace("?r=receipt/view-multiple-claim&rid=" + rid);
        }
    });
});

$("#plate-no").change(function(){
    $.get("?r=viecle/viecle-detail", {
        VID: $(this).val()
    }, function(data){
        $("#viecle-name").val( data.viecle_name );
        $("#viecle-model").val( data.viecle_model );
        $("#year").val( data.year );
        $("#engine-code").val( data.engine_code );
        $("#body-code").val( data.body_code );

        $("#fullname").val( data.fullname );
        $("#viecle-address").val( data.address );
        $("#phone").val( data.phone );
    });
});

$("#claim-no").autocomplete({
    source: function(request, response){
        $.get("?r=viecle/claim", {
            VID: $("#plate-no").val(), 
            term: request.term
        }, function(data){
           response(data);
        });
    },
});