
// Delete Parent categories with all sub-categories
$(document).on('click', '.delete-btn', function(e) {
    e.preventDefault();
    var self = $(this);
    swal({
            title: "Видалити?",
            text: "Ви впевнені, що хочете видалити цю категорію?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Так, видалити!",
            cancelButtonText: "Відмінити",
            closeOnConfirm: true
        },
        function(isConfirm){
            if(isConfirm){
                swal("Видалено!","Категорія видалена", "success");
                setTimeout(function() {
                    self.parents(".delete_form").submit();
                }, 1000);
            }
            else{
                swal("Відмінено!","Ваша категорія не змінилась ", "error");
            }
        });
});


// Delete sub categories
$(document).on('click', '.delete-btn-sub', function(e) {
    e.preventDefault();
    var self = $(this);
    swal({
            title: "Видалити?",
            text: "Ви впевнені, що хочете видалити цю підкатегорію?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Так, видалити її!",
            cancelButtonText: "Відмінити",
            closeOnConfirm: true
        },
        function(isConfirm){
            if(isConfirm){
                swal("Видалено!","Підкатегорія видалена!", "success");
                setTimeout(function() {
                    self.parents(".delete_form_sub").submit();
                }, 1000);
            }
            else{
                swal("Відмінено!","Ваша підкатегорія не змінилась", "error");
            }
        });
});

// Delete Brands
$(document).on('click', '#delete-btn-brand', function(e) {
    e.preventDefault();
    var self = $(this);
    swal({
            title: "Ви впевнені?",
            text: "Ви впевнені, що хочете видалити цей бренд!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Так, видалити його!",
            cancelButtonText: "Відмінити",
            closeOnConfirm: true
        },
        function(isConfirm){
            if(isConfirm){
                swal("Видалено!","Бренд видалений", "success");
                setTimeout(function() {
                    self.parents(".delete_form_brand").submit();
                }, 1000);
            }
            else{
                swal("Відмінено!","Ваш бренд не змінився", "error");
            }
        });
});

// Delete Products
$(document).on('click', '#delete-product-btn', function(e) {
    e.preventDefault();
    var self = $(this);
    swal({
            title: "Ви впевнені?",
            text: "Ви впевнені, що хочете видалити цей продукт?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Так, видалите його!",
            cancelButtonText: "Відмінити",
            closeOnConfirm: true
        },
        function(isConfirm){
            if(isConfirm){
                swal("Видалено!","Продукт видалено", "success");
                setTimeout(function() {
                    self.parents(".delete_form_product").submit();
                }, 1000);
            }
            else{
                swal("Відмінено!","Ваш продукт не змінено", "error");
            }
        });
});



// Delete Users
$(document).on('click', '#delete-user-btn', function(e) {
    e.preventDefault();
    var self = $(this);
    swal({
            title: "Ви впевнені?",
            text: "Ви впевнені, що хочете видалити цього користувача?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Так, видалити його!",
            cancelButtonText: "Відмінити",
            closeOnConfirm: true
        },
        function(isConfirm){
            if(isConfirm){
                swal("Видалено!","Користувач видалений", "success");
                setTimeout(function() {
                    self.parents(".delete_form_user").submit();
                }, 1000);
            }
            else{
                swal("Відмінено","Користувач незмінений", "error");
            }
        });
});





// This is for the sub-categories drop-down.
// This will fire off when a user chooses the parent category in the
// add product page
$(document).ready(function($){
    $('#category').change(function(){
        $.get($(this).data('url'), {
                option: $(this).val()
            },
            function(data) {
                var subcat = $('#sub_category');
                subcat.empty();
                $.each(data, function(index, element) {
                    subcat.append("<option value='"+ element.id +"'>" + element.category + "</option>");
                });
            });
    });
});


// Generate a random SKU for a product
function GetRandom() {
    var myElement = document.getElementById("product_sku");
    myElement.value = Math.floor(100000000 + Math.random() * 900000000);
}



<!-- Menu Toggle Script -->
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

//# sourceMappingURL=app.js.map
