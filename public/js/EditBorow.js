$(document).ready(function () {

    $( "#borrow_borrow_date" ).change(function() {
        var mediaBorrowDuration =parseInt($(".borrow_duration").val()) ;
        console.log(mediaBorrowDuration);
        var borrowDate = new Date($("#borrow_borrow_date").val());
        console.log(borrowDate);
        var result = new Date(borrowDate);
        console.log(borrowDate.getDate());
        result.setDate(borrowDate.getDate() + mediaBorrowDuration);
        console.log(result);

        var dd = result.getDate();
        var mm = result.getMonth() +1 ;
        if(dd.toString().length < 2 ) {
            dd = '0'+dd;
            console.log(dd);
        }
        if(mm.toString().length < 2 ) {
            mm = '0'+mm;
            console.log(mm);
        }
        var y = result.getFullYear();

        var someFormattedDate = y + '-' + mm + '-' + dd;
        console.log(someFormattedDate);
        $( "#borrow_expiry_date").val(someFormattedDate);
    });
});

