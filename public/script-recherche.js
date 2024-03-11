
function goBack() {
    window.history.back();
}

$(document).ready(function () {
    $("#searchUser").on("input", function () {
        var searchValue = $(this).val().toLowerCase();
        $("#listutilisateurs li").each(function () {
            var userFullName = $(this).text().toLowerCase();
            $(this).toggle(userFullName.indexOf(searchValue) > -1);
        });
    });
});
