/**
 * @file     presentation.js
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Fonctions JQuery et Ajax
 * @details  Création des présentations en accordéons triables, toutes les fonctions Ajax CRUD pour les présentations et les catégories, validation du formulaire d'ajout/modification de présentations
 */

$(document).ready(function() {

    // Création des Catégories et Présentations
    $.ajax({
        url: 'index.php?Presentations&action=displayPresentations',
        type: 'POST',
        dataType: 'html',
        success: function(result, status) { 
            // Effacer le contenu de #display_presentations
            $("#display_presentations").empty();
            // Création du html des catégories et présentations
            $(result).appendTo("#display_presentations");
            sortable_accordion();
        }
        //error: function(resultat, statut, erreur) {},
        //complete: function(resultat, statut) {}
    });

    // Création des options du menu select #CategoryId dans le formulaire d'ajout/modification de présentation
    $.ajax({
        url: 'index.php?Categories&action=displayCategories', 
        type: 'POST',
        dataType: 'html',
        success: function(result, status) {
            $(result).appendTo("#CategoryId");
        }
    });

});

// Fonction pour accordéon triable
function sortable_accordion() {
    // Accordéon triable des catégories
    $('.section_accordion').accordion({
        collapsible: true, 
        active: false, 
        heightStyle: 'content', 
        header: '> section > h3'
    }).sortable({
        axis: 'y', 
        handle: 'h3', 
        items: '.section_sortable', 
        placeholder: 'section_highlight', 
        stop: function(event, ui) {
            ui.item.children('h3').triggerHandler('focusout');
            $(this).accordion('refresh');
       }
    });
    // Accordéon triable des présentations
    $('.article_accordion').accordion({
        collapsible: true, 
        active: false, 
        heightStyle: 'content', 
        header: '> article >h4'
    }).sortable({
        axis: 'y', 
        handle: 'h4', 
        items: '.article_sortable',
        placeholder: "article_highlight", 
        stop: function(event, ui) {
            ui.item.children('h4').triggerHandler('focusout');
            $(this).accordion('refresh');
        }
    });
}

// Initialisation et ouverture du modal du formulaire d'ajout/modification de présentation
$("#AddPresentation").on("click", function() {
    eraseForm();
    $('#error').addClass("invisible");
    $('#entry-form-modal').modal('show');
});

// Création des options du menu select #SortOrder dans le formulaire d'ajout/modification de présentation
$("#CategoryId").on("click", function() {
    $("#SortOrder").val(null);
});

// Création des options du menu select #SortOrder dans le formulaire d'ajout/modification de présentation
function DisplaySortOrder() {
    $.ajax({
        url: 'index.php?Presentations&action=displaySortOrder',
        type: 'POST',
        data: { 
                PresentationId: $('#PresentationId').val(), 
                CategoryId: $('#CategoryId').val(), 
            }, 
        dataType: 'html',
        success: function(orderList, status) {
            $("#SortOrder").empty();
            $(orderList).appendTo("#SortOrder");
            $("#UpdateCategory").removeClass("invisible");
            $("#ConfirmDeleteCategory").removeClass("invisible");
            $("#NewCategory").css("border","#ccc solid 1px");
            $("#NewCategory").attr("placeholder", "Nouvelle catégorie");
            $("#NewCategory").val("");
            $("#UpdateCategory").removeClass("hidden");
            $("#SaveUpdate").addClass("hidden");
        }
    });
}

$("#CategoryId").on("change", function() {
    DisplaySortOrder();
});

// Sauvegarde des données du formulaire d'ajout/modification de présentation
$("#SavePresentation").on("click", function() {
    $.ajax({
        url: 'index.php?Presentations&action=savePresentation',
        type: 'POST',
        data: { 
                PresentationId: $('#PresentationId').val(), 
                Title: $('#Title').val(), 
                Description: $('#Description').val(), 
                CategoryId: $('#CategoryId').val(), 
                SortOrder: $('#SortOrder').val(), 
                StartDate: $('#StartDate').val(), 
                StartTime: $('#StartTime').val(), 
                EndTime: $('#EndTime').val(), 
                Location: $('#Location').val(), 
                Animator: $('#Animator').val(), 
                Institution: $('#Institution').val(), 
            }, 
        dataType: 'html',
        success: function(result) {
            $("#display_presentations").empty();
            $(result).appendTo("#display_presentations");
            sortable_accordion();
        }
    });
});

// Modifier une présentation
function updatePresentation(id) {
    $.ajax({
        url: 'index.php?Presentations&action=updatePresentation',
        type: 'POST',
        data:  { PresentationId: id }, 
        dataType: 'json',
        success: function(data) {
            eraseForm();
            $('#error').addClass("invisible");
            $('#entry-form-modal-title').text("Modifier une présentation");
            $('#PresentationId').val(data.Id);
            $('#Title').val(data.Title);
            $('#Description').val(data.Description);
            $('#CategoryId').val(data.CategoryId);
            $('#StartDate').val(data.StartDate);
            $('#StartTime').val(data.StartTime);
            $('#EndTime').val(data.EndTime);
            $('#Location').val(data.Location);
            $('#Animator').val(data.Animator);
            $('#Institution').val(data.Institution);
            $('#entry-form-modal').modal('show');
            
            DisplaySortOrder();
        }
    });
}

// Effacer une présentation de la base de données
function deletePresentation(id) {
    $.ajax({
        url: 'index.php?Presentations&action=deletePresentation',
        type: 'POST',
        data:  { PresentationId: id }, 
        dataType: 'html',
        success: function(result) {
            $("#display_presentations").empty();
            $(result).appendTo("#display_presentations");
            sortable_accordion();
        }
    });
}


// Effacer le formulaire d'ajout/modification de présentation
function eraseForm() {
    $("input").val("");
    $("textarea").val("");
    $("select").val("0");
    $("PresentationId").val("0");
    $("input").css("border","#ccc solid 1px");
    $("textarea").css("border","#ccc solid 1px");
    $("select").css("border","#ccc solid 1px");
    $("#UpdateCategory").addClass("invisible");
    $("#SaveUpdate").addClass("hidden");
    $("#ConfirmDeleteCategory").addClass("invisible");
    setDateTime(90);
};


// Mettre les valeurs défaut pour la date, heure et durée dans le formulaire d'ajout/modification de présentation
function setDateTime(duration) {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var hours = ("0" + now.getHours()).slice(-2);
    var minutes = ("0" + now.getMinutes()).slice(-2);
    var seconds = ("0" + now.getSeconds()).slice(-2);
    var endHour = +hours + Math.floor(duration / 60);
    var endMinute = +minutes + duration % 60;
    if (endMinute > 59) {
        endMinute = ("0" + endMinute % 60).slice(-2);
        ("0" + endHour++).slice(-2);
        if (endHour > 23) {
            endHour = "00";
            day = ("0" + day++).slice(-2);
        }
    }
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var time = hours + ":" + minutes + ":" + seconds;
    var end = ("0" + endHour).slice(-2) + ":" + ("0" + endMinute).slice(-2) + ":" + seconds;
    console.log(end);
    $('#StartDate').attr("min", today);
    $('#StartDate').val(today);
    $("#StartTime").val(time);
    $("#EndTime").val(end);
    console.log($('#StartDate').val(), $("#StartTime").val(), $("#EndTime").val());
};


// VALIDATION

// Titre
$("#Title").on("input blur", function() {
    if ($("#Title").val().trim() == "") {
        $("#Title").css("border","red solid 2px");
        $("#Title").attr("placeholder", "Veuillez entrer un titre");
    }
    else {
        $("#Title").css("border","lightgreen solid 2px");
    }
});

// Description / Résumé
$("#Description").on("input blur", function() {
    if ($("#Description").val().trim() == "") {
        $("#Description").css("border","red solid 2px");
        $("#Description").attr("placeholder", "Veuillez entrer une description");
    }
    else {
        $("#Description").css("border","lightgreen solid 2px");
    }
});

// Catégorie
$("#CategoryId").on("input blur", function() {
    if ($("#CategoryId").val() == null) {
        $("#CategoryId").css("border","red solid 2px");
        $("#NewCategory").css("border","#ccc solid 1px");
        $("#NewCategory").attr("placeholder", "Nouvelle catégorie");
    }
    else {
        $("#CategoryId").css("border","lightgreen solid 2px");
    }
});

// Ordre
$("#SortOrder").on("input blur", function() {
    if ($("#SortOrder").val() == null || $("#SortOrder").val() == '0') {
        $("#SortOrder").css("border","red solid 2px");
    }
    else {
        $("#SortOrder").css("border","lightgreen solid 2px");
    }
});

// Date
$("#StartDate").on("input blur", function() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    if ($("#StartDate").val() == "" || $("#StartDate").val() < today) {
        $("#StartDate").css("border","red solid 2px");
    }
    else {
        $("#StartDate").css("border","lightgreen solid 2px");
    }
});

// Début
$("#StartTime").on("input blur", function() {
    if ($("#StartTime").val() == "") {
        $("#StartTime").css("border","red solid 2px");
    }
    else {
        $("#StartTime").css("border","lightgreen solid 2px");
    }
});

// Fin
$("#EndTime").on("input blur", function() {
    if ($("#EndTime").val() == "") {
        $("#EndTime").css("border","red solid 2px");
    }
    else {
        $("#EndTime").css("border","lightgreen solid 2px");
    }
});

// Emplacement
$("#Location").on("input blur", function() {
    if ($("#Location").val().trim() == "") {
        $("#Location").css("border","red solid 2px");
        $("#Location").attr("placeholder", "Veuillez entrer un emplacement")
    }
    else {
        $("#Location").css("border","lightgreen solid 2px");
    }
});

// Animateur
$("#Animator").on("input blur", function() {
    if ($("#Animator").val().trim() == "") {
        $("#Animator").css("border","red solid 2px");
        $("#Animator").attr("placeholder", "Veuillez entrer un animateur")
    }
    else {
        $("#Animator").css("border","lightgreen solid 2px");
    }
});

// Institution
$("#Institution").on("input blur", function() {
    if ($("#Institution").val().trim() == "") {
        $("#Institution").css("border","red solid 2px");
        $("#Institution").attr("placeholder", "Veuillez entrer une institution")
    }
    else {
        $("#Institution").css("border","lightgreen solid 2px");
    }
});

// Bouton de sauvegarde du formulaire
$("form").on("click change mousemove", function() {
    if ($("#Title").val() == "" || $("#Description").val() == "" || $("#CategoryId").val() == null || $("#StartDate").val() == "" || $("#StartTime").val() == "" || $("#EndTime").val() == "" || $("#Location").val() == "" || $("#Animator").val() == "" || $("#Institution").val() == "") {
        $("#SavePresentation").prop("disabled", true);
    }
    else {
        $("#SavePresentation").prop("disabled", false);
    }
});


// CATÉGORIES

// Activer le bouton ajouter si la valeur du input #NewCategory n'est pas vide.
$('#NewCategory').on("input blur", function() {
    //if ($("#NewCategory").val().trim() == "" && $("#SaveUpdate").hasClass("hidden")) {
    if ($("#NewCategory").val().trim() == "") {
        $("#AddCategory").prop("disabled", true);
        if ($("#CategoryId").val() == null) {
            $("#NewCategory").css("border","red solid 2px");
            $("#NewCategory").attr("placeholder", "Veuillez entrer une nouvelle catégorie");
        }
        else {
            $("#NewCategory").css("border","red solid 2px");
        }
    }
    else {
        $("#NewCategory").css("border","lightgreen solid 2px");
        $("#AddCategory").prop("disabled", false);
    }
});

// Sauvegarder une nouvelle category
$("#AddCategory").on("click", function() {
    var id = $("#CategoryId").val();
    $.ajax({
        url: 'index.php?Categories&action=saveCategory', 
        type: 'POST',
        data: { 
            CategoryName: $("#NewCategory").val(), 
            }, 
        dataType: 'html',
        success: function(data) {
            $("#CategoryId").empty();
            $(data).appendTo("#CategoryId");
            $("#CategoryId").children().last().attr("selected", true);
            ResetCategoryButtons(data.SortOrder);
        }
    });
});

// Mettre la description de la category dans l'input NewCategory pour la modifier
$("#UpdateCategory").on("click", function() {
    $.ajax({
        url: 'index.php?Categories&action=displayCategory', 
        type: 'POST',
        data: { CategoryId: $("#CategoryId").val() }, 
        dataType: 'html',
        success: function(result) {
            $("#NewCategory").val(result);
            $("#NewCategory").css("border","#ccc solid 1px");
            $("#NewCategory").focus();
            $("#AddCategory").addClass("hidden");
            $("#UpdateCategory").addClass("hidden");
            $("#SaveUpdate").removeClass("hidden");
            $("#AddCategory").prop("disabled", true);
            $("#CategoryId").css("border","#ccc solid 1px");
        }
    });
});

// Sauvegarder la modification de la category
$("#SaveUpdate").on("click", function() {
    var id = $("#CategoryId").val();
    $.ajax({
        url: 'index.php?Categories&action=saveCategory', 
        type: 'POST',
        data: { 
            CategoryId: id, 
            CategoryName: $("#NewCategory").val(), 
            }, 
        dataType: 'html',
        success: function(data) {
            $("#CategoryId").empty();
            $(data).appendTo("#CategoryId");
            $("#CategoryId").val(id);
            ResetCategoryButtons(data.SortOrder);
        }
    });
});

// Initialiser les boutons CRUD des catégories
function ResetCategoryButtons(SortOrder) {
    $("#CategoryId").css("border","#ccc solid 1px");
    $("#NewCategory").val("");
    $("#NewCategory").css("border","#ccc solid 1px");
    $("#UpdateCategory").removeClass("hidden");
    $("#SaveUpdate").addClass("hidden");
    $("#AddCategory").prop("disabled", true);
    $('#SortOrder').val(SortOrder);
    $("#UpdateCategory").removeClass("invisible");
    $("#ConfirmDeleteCategory").removeClass("invisible");
    $("#AddCategory").removeClass("hidden");
}

// Confirmation pour effacer une category
$("#ConfirmDeleteCategory").on("click", function() {
    $.ajax({
        url: 'index.php?Categories&action=confirmDeleteCategory', 
        type: 'POST',
        data: { 
            CategoryId: $("#CategoryId").val(), 
            }, 
        dataType: 'html',
        success: function(data, status) {
            $("#DeletedPresentations").empty();
            $("#DeletedPresentations").html(data);
            $("#DeleteCategoryCollapse").collapse('show');
            $("#ConfirmDeleteCategory").addClass("invisible");
        }
    });
});

// Confirmation pour effacer une category
$("#DeleteCategory").on("click", function() {
    $.ajax({
        url: 'index.php?Categories&action=deleteCategoryPresentations', 
        type: 'POST',
        data: { 
            CategoryId: $("#CategoryId").val(), 
            }, 
        dataType: 'html',
        success: function(data, status) {
            $("#CategoryId").empty();
            $(data).appendTo("#CategoryId");
            $("#DeleteCategoryCollapse").collapse('hide');
            $("#ConfirmDeleteCategory").addClass("invisible");
            $("#UpdateCategory").addClass("invisible");
        }
    });
});

// Remettre le bouton de suppression initial si la confirmation de suppression est annulée 
$("#CancelDeleteCategory").on("click", function() {
    $("#ConfirmDeleteCategory").removeClass("invisible");
});
