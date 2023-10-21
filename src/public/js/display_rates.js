'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const openReviews = document.querySelectorAll('.open-reviews');
    const closeReview = document.getElementById('close_review');
    const reviews = document.getElementById('reviews');
    const review = document.getElementById('review');

    for (let i = 0; i < openReviews.length; i++) {
        openReviews.item(i).addEventListener('click', function () {
            if (review.classList.contains('show')) {
                review.classList.remove('show');
            }
            else {
                review.classList.add('show');
            }
        });
    }

    closeReview.addEventListener('click', function () {
        if (review.classList.contains('show')) {
            review.classList.remove('show');
        }
        else {
            review.classList.add('show');
        }
    });

    reviews.addEventListener('click', function () {
        if (review.classList.contains('show')) {
            review.classList.remove('show');
        }
        else {
            review.classList.add('show');
        }
    });
});
