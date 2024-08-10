const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;

let isDarkMode = false;

darkModeToggle.addEventListener('click', () => {
    isDarkMode = !isDarkMode;
    if (isDarkMode) {
        body.classList.add('dark-mode');
    } else {
        body.classList.remove('dark-mode');
    }
});


// css dark mode
// *{
//     margin: 0;z
//     padding: 0;
// }
// body{
//     font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
//     margin:40% auto;
//     padding: 0%;
// }

// /* --------------------------------------------------------------------- */
// /* --------------------------------------------------------------------- */
// /* --------------------------------------------------------------------- */

// body {
//     background-color: #ffffff;
//     color: #000;
// }

// /* أسلوب وضع الليل */
// body.dark-mode {
//     background-color: #323232;
//     color: #fff;
// }

// .img {
//     cursor:pointer;
//     color: #333;
//     background:none ;
//     border: none;
//     transform: translate(-5px,0px);
//     width: 35px;
//     height: 35px; 
//     background-image: none;
// }


// html dark mode

// /* <div class="search">
// <!-- <i class="fa-solid fa-magnifying-glass pe-3 ps-3 w-100"></i> -->
// <img class="img" id="darkModeToggle" src="./img/icons8-dark-mode-50.png" alt="404">
// </div> */
// ----------------------------------------------------------------------------------
