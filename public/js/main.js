/* ******************** Document Ready START ********************** */

$(document).ready(function() {
	console.log('ready!');
	$('#search-result').hide();
	$('#product-update').hide();
	$('#Search').keypress(function(e) {
		var key = e.which;
		if (key == 13) {
			search();
		}
	});
	/* ******************** Document Ready END ********************** */

	/* ******************** KEYBOARD FUNCTIONS START ********************** */

	$('#Search').keyup(function(e) {
		if ($('#Search').val().length < 3) {
			$('#search-result').hide();
			$('#search-result').empty();
			return;
		}
		search();
	});
	$('#inputName, #inputCategory, textarea#inputDescription').focusin(function() {
		displayEmpty();
	});

	/* ******************** KEYBOARD FUNCTIONS END ********************** */

	/* ******************** Button Click Functions START ********************** */

	$('#btn-search').click(function(e) {
		e.preventDefault();
		search();
	});

	$('#create-product').click(function(e) {
		e.preventDefault();
		CreateProduct();
	});
	$('#update-product').on('click', function(e) {
		alert(e);
		e.preventDefault();
		UpdateProduct();
	});
	$('#delete-product').click(function(e) {
		e.preventDefault();
		DeleteProduct();
	});
});
/* ******************** Button Click Functions END ********************** */

/* ******************** PRODUCT FUNCTIONS START ********************** */

function search() {
	var data = $('#Search').val();
	if (data.length == 0) {
		$('#search-result').hide();
		$('#search-result').empty();
		return;
	}
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'Search' }
	}).done(function(msg) {
		Display(msg);
	});
}

/* ******************** PRODUCT SEARCH FUNCTIONS END ********************** */

/* ******************** PRODUCT DELETE FUNCTIONS START ********************** */

function deleteProduct(ID) {
	var data = { id: ID };
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'Delete' }
	}).done(function(msg) {
		$('.product').empty();
		$('.product').append(msg);
		$('.product').show('slow');

		// reset product create form
	});
}

/* ******************** PRODUCT DELETE FUNCTIONS END ********************** */

/* ******************** PRODUCT CREATE FUNCTIONS START ********************** */
function CreateProduct() {
	var name = $('#inputName').val();
	var category = $('#inputCategory').val();
	var description = $('textarea#inputDescription').val();
	if (name == '' || category == '' || description == '') {
		checkEmpty();
		return;
	}
	var data = { name: name, category: category, description: description };
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'Create' }
	}).done(function(msg) {
		Display(msg);
		$('.product-create').trigger('reset'); // reset product create form
	});
}

/* ******************** PRODUCT CREATE FUNCTION END ********************** */

/* ******************** PRODUCT UPDATE FUNCTION START ********************** */
$('#categorySelect').on('change', function() {
	var Type;
	var category = this.value;
	if (category == 0) {
		$('.product-update').empty();
		return;
	}
	if ($('#categorySelect').hasClass('Update')) {
		Type = 'Update';
	} else if ($('#categorySelect').hasClass('Read')) {
		Type = 'Read';
	} else if ($('#categorySelect').hasClass('Delete')) {
		Type = 'Delete';
	}
	var data = {
		category: category,
		Type: Type
	};
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'CategorySelect' }
	}).done(function(msg) {
		$('.product').empty();
		$('.product').append(msg);
		$('.product').show('slow');

		// reset product create form
	});
});

function postUpdate(ID) {}

function loadProduct(ID) {
	var data = { id: ID };
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'Details' }
	}).done(function(msg) {
		$('.product').empty();
		$('.product').append(msg);
		$('.product').show('slow');

		// reset product create form
	});
}

function UpdateProduct(ID) {
	var name = $('#inputName').val();
	var category = $('#inputCategory').val();
	var description = $('textarea#inputDescription').val();
	if (name == '' || category == '' || description == '') {
		checkEmpty();
		return;
	}
	var data = { id: ID, name: name, category: category, description: description };
	$.ajax({
		method: 'POST',
		url: '../pages/functions.php',
		data: { data: data, action: 'Update' }
	}).done(function(msg) {
		$('.product').empty();
		$('.product').append(msg);
		$('.product').show('slow');

		// reset product create form
	});
}

/* ******************** PRODUCT UPDATE FUNCTION END ********************** */

/* ******************** PRODUCT FUNCTIONS END ********************** */

/* ******************** UTILITY FUNCTION START ********************** */

function displayEmpty() {
	if ($('#inputName').hasClass('is-invalid')) {
		$('#inputName').removeClass('is-invalid');
	} else if ($('#inputCategory').hasClass('is-invalid')) {
		$('#inputCategory').removeClass('is-invalid');
	} else if ($('textarea#inputDescription').hasClass('is-invalid')) {
		$('textarea#inputDescription').removeClass('is-invalid');
	}
}

function checkEmpty() {
	if ($('#inputName').val() == '') {
		$('#inputName').addClass('is-invalid');
	}

	if ($('#inputCategory').val() == '') {
		$('#inputCategory').addClass('is-invalid');
	}

	if ($('textarea#inputDescription').val() == '') {
		$('textarea#inputDescription').addClass('is-invalid');
	}
}

function Display(msg) {
	$('#search-result').hide();
	$('#search-result').empty();
	$('#search-result').append(msg);
	$('#search-result').show();
}
/* ****************** UTILITY FUNCTION END ************************ */
