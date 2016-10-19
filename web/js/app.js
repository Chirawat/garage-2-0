$(document).ready(function () {
    var maintenance = [];
    var part = [];
    var invoice = [];
    var id = 1;
    var list = [];
    /* Bind enter key to function */
    $("#maintenance-list").bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#maintenance-price").bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#part-list").bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#part-price").bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            //console.log("enter");
            enterDescription();
        }
    });
    $("#maintenance-add").click( function () {
        enterDescription();
    });
    $("#part-add").click( function () {
        enterDescription();
    });
    

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
            appendRow += '<tr id="' + i + '"><td style="text-align: Center;">' + i + '</td>';
            if (typeof maintenance[i] !== 'undefined') {
                // col 2
                appendRow += '<td>' + maintenance[i].list + '</td>\
                    <td>' + maintenance[i].price + '</td>';
                // col 3
                appendRow += '<td>\
                    <button id="maintenance-del" class="btn btn-danger btn-xs"> \
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
                    <button id="part-del" class="btn btn-danger btn-xs"> \
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
        $("table > tbody").html("");
        // append row
        $("table > tbody").html(appendRow);
    }

    function enterDescription() {
        // check, empty?
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
        if ($("#maintenance-list").val() != "") {
            maintenance.push({
                row: id
                , list: $("#maintenance-list").val()
                , price: $("#maintenance-price").val()
            });
        }
        if ($("#part-list").val() != "") {
            part.push({
                row: id
                , list: $("#part-list").val()
                , price: $("#part-price").val()
            });
        }
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
        id++;
    }
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

    function updateTableIndex() {
        var row = $("tbody > tr");
        for (var i = 0, nRow = row.size(); i < nRow; i++) {
            // select first column
            var col = $(row).eq(i).find("td");
            // update text
            $(col).eq(0).text(i + 1);
        }
    }
    $("#maintenance-list").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "index.php?r=description/description-list"
                , dataType: "json"
                , data: {
                    term: request.term
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
                    term: request.term
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
        //if (maintenance.length != 0 || part.length != 0) {
        // get quotation info
        quotation_info = {
            customerType: $("#customer-type:checked").val()
            , claimNo: $("#claim-no").val()
            , insuranceCompany: $("#insurance-company option:selected").val()
            , damageLevel: $("#damage-level").val()
            , damagePosition: $("#damage-position").val()
            , vieclePlateNo: $("#plate-no option:selected").val()
        };
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
        console.log(quotation_info);
        /* Send information to server */
        $.ajax({
            url: "index.php?r=quotation/quotation-save"
            , type: 'json'
            , data: {
                quotation_info: quotation_info
                , maintenance_list: maintenance
                , part_list: part
            , }, 
//            success: function (data) {
//                var id = $("#quotationId").val()
//                window.location.replace("?r=quotation/view&quotation_id=" + id);
//            }
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
        var quotationId = $("#quotationId").val();
        var hrefStr = "index.php?r=quotation/report&quotation_id=" + quotationId;
        $("#btn-print").attr("href", hrefStr);
        //console.log();
    });
    var pathname = window.location.href;
    if (pathname.indexOf("view") != -1) {
        $("#btn-print").removeClass('disabled');
        $("#btn-print-invoice").removeClass('disabled'); // for invoice print
        $("#btn-register").addClass('disabled');
    }
    /////////////////////////////////////////////////////////////////////////////
    ////////////// Invoice //////////////////////////////////////////////////////
    $("#btn-add-invoice").click(function () {
        // check, empty?
        if ($("#maintenance-list").val() == "") {
            alert("กรุณาป้อนรายการ");
            return false;
        }
        // prepare append row.
        var appendRow = '<tr id=' + id + '> \
            <td style="text-align: center;">' + id + '</td> \
            <td>' + $("#maintenance-list").val() + '</td> \
            <td style="text-align: right;">' + $("#maintenance-price").val() + '</td> \
            <td> \
                <button id="btn-del-invoice"class="btn btn-danger btn-xs"> \
                    <span class="glyphicon glyphicon-remove"></span> \
                </button> \
            </td></tr>';
        $("table > tbody").append(appendRow);
        // Push data into object.
        if ($("#maintenance-list").val() != "") {
            invoice.push({
                row: id
                , list: $("#maintenance-list").val()
                , price: $("#maintenance-price").val()
            });
        }
        // Cal total
        calTotalInvoice();
        // clear text box value
        $("#maintenance-list").val("");
        $("#maintenance-price").val("");
        id++;
    });
    $("table#myTable").on("click", "#btn-del-invoice", function (event) {
        var closestRow = $(this).closest("tr");
        // remove row
        closestRow.remove();
        // calulate total
        updateTableIndex();
    });

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
    $("#maintenance-list").keyup(function () {
        //console.log("pressed");
        $("#btn-save-invoice").removeClass('disabled');
    });
    $("#btn-save-invoice").on("click", function (event, ui) {
        // error, customer cannot be blank!
        if ($("#customer").val() == "") window.alert("ต้องเลือกลูกค้าก่อน");
        $.ajax({
            url: "index.php?r=invoice/create"
            , type: 'json'
            , data: {
                customer: $("#customer").val()
                , invoice_id: $("#invoiceId").val()
                , invoice_description: invoice
            }
            , success: function (data) {
                var id = $("#quotationId").val();
                //window.location.replace("?r=invoice/view");
            }
        });
    });
    $("#invoiceId").keyup(function () {
        //console.log("keyup!")
        $("#viewInovoice").removeClass('disabled');
    });
    $("#viewInovoice").click(function () {
        window.location.replace("?r=invoice/view&invoice_id=" + $("#invoiceId").val());
    });

    function formatMoney(n, c, d, t) {
        //var n = this, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "." : d, t = t == undefined ? "," : t, s = n < 0 ? "-" : "", i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
});