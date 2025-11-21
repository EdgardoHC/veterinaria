$(document).ready(function () {
    $("#frmLogin").submit(function (e) {
        e.preventDefault();
        $.post("controller/LoginController.php",
            $(this).serialize() + "&accion=login",
            function (res) {
                if (res.ok) {
                   window.location.href = "index.php?page=dashboard";
                } else {
                    $("#msg").text(res.msg).css("color", "red");
                }
            },
            "json"
        );
    });
});
