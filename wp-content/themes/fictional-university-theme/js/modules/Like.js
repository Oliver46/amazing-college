import $ from 'jquery'; //gives us access to Jquery library
// Custom REST API EndPoints for "Like" Actions | It does not perform the Default REST API behavior
class Like {
    constructor() {
        this.events();
    }
    //listening | Front end javascript communicates with backend PHP (function.php | like-route.php)
    events(){
        $(".like-box").on("click", this.ourClickDispatcher.bind(this)) // it passes information
    }
    //methods
    ourClickDispatcher(e) {
        //console.log($(e.target));
        var currentLikeBox = $(e.target).closest(".like-box"); //guarantees that it will point to the correct element or selector .like-box
        console.log(currentLikeBox);

        if(currentLikeBox.attr('data-exists') == 'yes') { // attr. pull fresh data attributes
            this.deleteLike(currentLikeBox);
        }else{
            this.createLike(currentLikeBox);
        }

        // if(currentLikeBox.data('exists') == 'yes') {
        //     this.deleteLike(currentLikeBox);
        // }else{
        //     this.createLike(currentLikeBox);
        // }


    }
    createLike(currentLikeBox) {
        $.ajax({ // Send request to a custom API URL | $.ajax method
            //properties
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //secret nonce value | proof we are who we say we are
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike', //the string containing the URL (custom endpoints) to which the request is sent.
            type:'POST', // http request
            data: {'professorId': currentLikeBox.data('professor') }, //sending data | add data in my request url
            success: (response) => { //Run if request is successful
                currentLikeBox.attr('data-exists', 'yes'); //filling the heart
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); //parseInt switch text into number | update number
                likeCount++; //increment number
                currentLikeBox.find(".like-count").html(likeCount)//update html live number
                currentLikeBox.attr("data-like", response); //updates on the fly
                //console.log(likeCount);
                console.log(response);
            },
            error: (response) => { //Run if request fails
                console.log(response);
            }
        });
    }
    deleteLike(currentLikeBox) {
        $.ajax({ // Send request to a custom API URL
            //properties
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //secret nonce value | proof we are who we say we are
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike', //the string containing the URL (custom endpoints) to which the request is sent.
            data: {'like': currentLikeBox.attr('data-like')}, //send property to server
            type:'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no'); //unfilling the heart
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); //parseInt switch text into number | update number
                likeCount--; //decrement number
                currentLikeBox.find(".like-count").html(likeCount)//update html live number
                currentLikeBox.attr("data-like", ''); //updates on the fly
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Like