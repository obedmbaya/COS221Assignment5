//Functions below control image carousel on view page
let currImage = 0;
const imgArray = [
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403147989_800x.png?v=1686754731',
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403344597_800x.jpg?v=1686754737',
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403475669_800x.jpg?v=1686754742',
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403573973_800x.jpg?v=1686754929',
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403803349_800x.jpg?v=1686754926',
    'https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403999957_800x.jpg?v=1686755091'
];

function selectImage(index) {
    currImage = index;
    updateImage();
    updateThumbnails();
}

function nextImage() {
    currImage = (currImage + 1) % imgArray.length;
    updateImage();
    updateThumbnails();
}

function prevImage() {
    currImage = (currImage - 1 + imgArray.length) % imgArray.length;
    updateImage();
    updateThumbnails();
}

function updateImage() {
    document.querySelector('.product-main-image').style.backgroundImage = `url('${imgArray[currImage]}')`;
}

function updateThumbnails() {
    const thumbnails = document.querySelectorAll('.image-thumbnail');
    thumbnails.forEach((thumb, index) => {
        if (index === currImage) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

//Carousel for recommended products on view page
function scrollRecommended(direction) {
    const carousel = document.querySelector('.recommended-carousel');
    carousel.scrollBy({ left: direction, behavior: 'smooth' });
}

//Allows user to select/deselect stars in rating section of view page
function setRating(rating) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}
