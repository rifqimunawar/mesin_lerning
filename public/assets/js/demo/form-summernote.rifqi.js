/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 5
Version: 5.3.3
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin/
*/

var handleSummernote = function() {
	$(".summernote").summernote({
		placeholder: "isi surat",
		height: $(window).height() * 0.3
	});
};

var FormSummernote = function () {
	"use strict";
	return {
		//main function
		init: function () {
			handleSummernote();
		}
	};
}();

$(document).ready(function() {
	FormSummernote.init();
});
