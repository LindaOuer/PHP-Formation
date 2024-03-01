$(document).ready(function () {
    // call fetchData function
    fetchData();

    //initialize datatables
    let table = new DataTable("#myTable");

    // function to display image before upload
    $("input.image").change(function () {
        var file = this.files[0];
        var url = URL.createObjectURL(file);
        $(this).closest(".row").find(".preview_img").attr("src", url);
    });

    // function to fetch data from database
    function fetchData() {
        $.ajax({
            url: "../Controller/StudentAjax.php?action=fetchData",
            type: "GET",
            dataType: "json",
            success: function (response) {
                // Handle successful response
                table.clear().draw();
                $.each(response.data, function (index, value) {
                    table.row
                        .add([
                            value.Id,
                            value.firstName,
                            value.lastName,
                            value.Image === "default_profile.jpg"
                                ? '<img src="./images/' +
                                  value.Image +
                                  '" style="width:50px;height:50px;border:2px solid gray;border-radius:8px;object-fit:cover">'
                                : '<img src="./images/uploads/' +
                                  value.Image +
                                  '" style="width:50px;height:50px;border:2px solid gray;border-radius:8px;object-fit:cover">',
                            '<Button type="button" class="btn editBtn" value="' +
                                value.Id +
                                '"><i class="fa-solid fa-pen-to-square fa-xl"></i></Button>' +
                                '<Button type="button" class="btn deleteBtn" value="' +
                                value.Id +
                                '"><i class="fa-solid fa-trash fa-xl"></i></Button>' +
                                '<input type="hidden" class="delete_image" value="' +
                                value.Image +
                                '">',
                        ])
                        .draw(false);
                });
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error("AJAX request failed:", status, error);
            },
        });
    }

    // function to insert data to database
    $("#insertForm").on("submit", function (e) {
        $("#insertBtn").attr("disabled", "disabled");
        e.preventDefault();
        $.ajax({
            url: "../Controller/StudentAjax.php?action=insertData",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                var response = JSON.parse(response);
                if (response.statusCode == 200) {
                    $("#offcanvasAddStudent").offcanvas("hide");
                    $("#insertBtn").removeAttr("disabled");
                    $("#insertForm")[0].reset();
                    $(".preview_img").attr(
                        "src",
                        "./images/default_profile.jpg"
                    );
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    fetchData();
                } else if (response.statusCode == 500) {
                    $("#offcanvasAddStudent").offcanvas("hide");
                    $("#insertBtn").removeAttr("disabled");
                    $("#insertForm")[0].reset();
                    $(".preview_img").attr(
                        "src",
                        "./images/default_profile.jpg"
                    );
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                } else if (response.statusCode == 400) {
                    $("#insertBtn").removeAttr("disabled");
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error("AJAX request failed:", status, error);
            },
        });
    });

    // function to edit data
    $("#myTable").on("click", ".editBtn", function () {
        var id = $(this).val();
        $.ajax({
            url: "../Controller/StudentAjax.php?action=fetchSingle",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
            },
            success: function (response) {
                var data = response.data;
                $("#editForm #id").val(data.Id);
                $("#editForm input[name='first_name']").val(data.firstName);
                $("#editForm input[name='last_name']").val(data.lastName);
                data.Image === "default_profile.jpg"
                    ? $("#editForm .preview_img").attr(
                          "src",
                          "./images/" + data.Image + ""
                      )
                    : $("#editForm .preview_img").attr(
                          "src",
                          "./images/uploads/" + data.Image + ""
                      );
                $("#editForm #image_old").val(data.Image);
                // show the edit user offcanvas
                $("#offcanvasEditStudent").offcanvas("show");
            },
        });
    });

    // function to update data in database
    $("#editForm").on("submit", function (e) {
        $("#editBtn").attr("disabled", "disabled");
        e.preventDefault();
        $.ajax({
            url: "../Controller/StudentAjax.php?action=updateData",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                var response = JSON.parse(response);
                if (response.statusCode == 200) {
                    $("#offcanvasEditStudent").offcanvas("hide");
                    $("#editBtn").removeAttr("disabled");
                    $("#editForm")[0].reset();
                    $(".preview_img").attr(
                        "src",
                        "./images/default_profile.jpg"
                    );
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    fetchData();
                } else if (response.statusCode == 500) {
                    $("#offcanvasEditStudent").offcanvas("hide");
                    $("#editBtn").removeAttr("disabled");
                    $("#editForm")[0].reset();
                    $(".preview_img").attr(
                        "src",
                        "./images/default_profile.jpg"
                    );
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                } else if (response.statusCode == 400) {
                    $("#editBtn").removeAttr("disabled");
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error("AJAX request failed:", status, error);
            },
        });
    });

    // function to delete data
    $("#myTable").on("click", ".deleteBtn", function () {
        if (confirm("Are you sure you want to delete this student?")) {
            var id = $(this).val();
            var delete_image = $(this)
                .closest("td")
                .find(".delete_image")
                .val();
            $.ajax({
                url: "../Controller/StudentAjax.php?action=deleteData",
                type: "POST",
                dataType: "json",
                data: {
                    id,
                    delete_image,
                },
                success: function (response) {
                    if (response.statusCode == 200) {
                        fetchData();
                        $("#successToast").toast("show");
                        $("#successMsg").html(response.message);
                    } else if (response.statusCode == 500) {
                        $("#errorToast").toast("show");
                        $("#errorMsg").html(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX error
                    console.error("AJAX request failed:", status, error);
                },
            });
        }
    });
});
