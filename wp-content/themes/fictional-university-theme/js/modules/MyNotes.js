import $ from 'jquery';
//Connect(communicate) My Notes Front End with REST API as BackEnd
class MyNotes {
    constructor() {
        this.events();
    }
    events() {
        //When you click anywhere in the parent unorder list #my-notes if the interior element that you click on matches the selector (class) fire the callback method
        $("#my-notes").on("click", ".delete-note", this.deleteNote);
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
        $(".submit-note").on("click", this.createNote.bind(this));
    }
    
    //Methods will go here
    editNote(e){
        var thisNote = $(e.target).parents("li");
        console.log(thisNote);
        if(thisNote.data("state") == "editable") { //first time will value to false
            this.makeNoteReadOnly(thisNote);   
        }else{
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote){
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    }

    makeNoteReadOnly(thisNote){
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");
    }

    //delete Note
    deleteNote(e) { //Send the delete request to the right API
        var thisNote = $(e.target).parents("li");
        console.log(thisNote.data('id'));
        //$.ajax (method | jquery tool) request controls the type of request you are sending
       $.ajax({
           beforeSend: (xhr) => {
               xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //secret nonce value | proof we are who we say we are
           },
           url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
           type: 'DELETE', //Type of request
           success: (response) => { //if the request is successful
               thisNote.slideUp(); 
               console.log("congrats");
               console.log(response);
               if(response.userNoteCount < 5) {
                   $(".note-limit-message").removeClass("active");
               }
           }, //provides a function if the request is successful
           error: (response) => {
            console.log("Sorry");
            console.log(response);
           }, //function if the request fails

       });
    }

    //Update Note
    updateNote(e) { //Send the delete request to the right API
        var thisNote = $(e.target).parents("li");
        console.log(thisNote.data('id'));
        //$.ajax (method | jquery tool) request controls the type of request you are sending
        var ourUpdatedPost = {
            'title': thisNote.find(".note-title-field").val(), //get the value of that field
            'content': thisNote.find(".note-body-field").val(),
        }
        //console.log(thisNote.find(".note-title-field").val());
        //console.log(thisNote.find(".note-body-field").val());
        $.ajax({
           beforeSend: (xhr) => {
               xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
           },
           url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
           type: 'POST', //Type of request
           data: ourUpdatedPost, //Data that you want to send a long with the request
           success: (response) => { //if the request is successful
               this.makeNoteReadOnly(thisNote);
               console.log("congrats");
               console.log(response);
           }, //provides a function if the request is successful
           error: (response) => {
            console.log("Sorry");
            console.log(response);
           }, //function if the request fails

       });
    }

    //Create Note
    createNote(e) { //Send the delete request to the right API
     
        //construct an object
        var ourNewPost = {
            'title': $(".new-note-title").val(), //get the value of that field
            'content': $(".new-note-body").val(),
            'status': 'publish' //default is draft | publish private
        }
        //console.log(thisNote.find(".note-title-field").val());
        //console.log(thisNote.find(".note-body-field").val());
        $.ajax({
           beforeSend: (xhr) => {
               xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
           },
           url: universityData.root_url + '/wp-json/wp/v2/note/', //url to create a new note
           type: 'POST', //Type of request | Send data
           data: ourNewPost, //Data that you want to send a long with the request
           success: (response) => { //if the request is successful
                $(".new-note-title, .new-note-body").val(''); //Empty fields if the request is successful
                $(`
                <li data-id="${response.id}">
                <input readonly class="note-title-field" value="${response.title.raw}">
                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>    
                </li>
                `).prependTo("#my-notes").hide().slideDown(); // Add it to the unorder list
                console.log("congrats");
               console.log(response);
           }, //provides a function if the request is successful
           error: (response) => {
            if(response.responseText == "You have reached your note limit."){
                $(".note-limit-message").addClass("active");
            }
            console.log("Sorry");
            console.log(response);
           }, //function if the request fails

       });
    }
}
export default MyNotes;