/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
require('bootstrap');



$(document).ready(function() {

   $(".post-like-btn").on('click', function () {

      var postBtn = $(this);
      var postId = postBtn.attr('post-id');


      $.ajax({
         type: "POST",
         dataType: "html",
         url: "/post/" + postId + "/like",
         success: function(response){
            var responseData = $.parseJSON(response);
            if (responseData.success) {
               postBtn.children(".like-count").text(responseData.likeCount);

            }
         }
      });
   });

   $(".comment-like-btn").on('click', function () {

      var commentBtn = $(this);
      var commentId = commentBtn.attr('post-comment-id');

      $.ajax({
         type: "POST",
         dataType: "html",
         url: "/comment/" + commentId + "/like",
         success: function(response){
            var responseData = $.parseJSON(response);
            if (responseData.success) {
               commentBtn.children(".like-count").text(responseData.likeCount);

            }
         }
      });
   });
});