import $ from 'jquery'; // in order to use jquery library
//Search Overlay Javascript
class Search { //reusable blue print
    // 1. describe and create/initiate our object
    constructor() { //Any code we place within the constructor function will be excecuted as soon as we create an object using our class
       //this refers to the current object
       // Properties
        this.addSearchHTML();
        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.isOverLayOpen = false; //keep track of the overlay
        this.searchField = $("#search-term"); //select the input field
        this.typingtimer;
        this.events(); //Look out for events
        this.isSpinnerVisible = false; //keep track of the state of the spin
        this.previousValue;
        
    }
    // 2. Events (Methods)
        //Look out for this events
        events(){ //Fire this methods when they get called
            this.openButton.on("click", this.openOverlay.bind(this));//Fires openOverlay method  
            this.closeButton.on("click", this.closeOverlay.bind(this)); //Fires close Overlay method 
            $(document).on("keydown", this.keyPressDispatcher.bind(this)); //target page as a whole
            this.searchField.on("keyup", this.typingLogic.bind(this));
        }
    // 3. Methods (function, action...)
    typingLogic() {
        if(this.searchField.val() != this.previousValue){ // Only if the current value doesn't equal the previous value
            clearTimeout(this.typingtimer); //every time you press a key, it resets the timeout.
            if(this.searchField.val()){ //only if the searchField.val() is not blank
                if(!this.isSpinnerVisible) { //if spinner is not Visible (false)
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible =  true;
                    }
                this.typingtimer = setTimeout(this.getResults.bind(this), 700); // call getResults function after 2 seconds
            }else { // if searchField.val() is blank
                this.resultsDiv.html(''); //empty out html
                this.isSpinnerVisible = false;
            }
            
        }
        
        this.previousValue = this.searchField.val();
    }
    getResults() {
        //$.getJSON sends a get request
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            //template titeral
            this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                    ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
                     ${results.generalInfo.length ? '</ul>': ''}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View All Programs</a></p>`}
                    ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                    ${results.programs.length ? '</ul>': ''}

                    <h2 class="search-overlay__section-title">Professors</h2>
                    ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search.</p>`}
                    ${results.professors.map(item => `
                        <li class="professor-card__list-item">
                        <a class="professor-card" href="${item.permalink}">
                        <img class="professor-card__image" src="${item.image}">
                        <span class="professor-card__name">${item.title}</span>
                        </a>
                        </li>
                    `).join('')}
                    ${results.professors.length ? '</ul>': ''}
                </div>
                <div class="one-third">
                <h2 class="search-overlay__section-title">Campuses</h2>
                ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/programs">View All Programs</a></p>`}
                ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                ${results.campuses.length ? '</ul>': ''}

                <h2 class="search-overlay__section-title">Events</h2>
                ${results.events.length ? '' : `<p>No events match that search. <a href="${universityData.root_url}/events">View All Events</a></p>`}
                ${results.events.map(item => `
                <div class="event-summary">
                    <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>  
                    </a>
                    <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                    <p>${item.description}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
                    </div>
                </div>
                `).join('')}
                </div>
            </div>
            `);
            this.isSpinnerVisible = false;
        }); //url you want to send a request to, function you want to run after the url or server response

        //delete this code a bit later on
        // $.when(
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
        //     ).then((posts, pages) => {
        //     var combineResults = posts[0].concat(pages[0]);
        //         this.resultsDiv.html(`
        //      <h2 class="search-overlay__section-title">General Information</h2>
        //      ${combineResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
        //         ${combineResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
        //      ${combineResults.length ? '</ul>': ''}
        //      `);
        //      this.isSpinnerVisible = false;
        // }, () => {
        //     this.resultsDiv.html('<p>Unexpected error; please try again.</p>');
        // });

        //Different code
        // $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => {
        //      //var testArray = ['red', 'orange', 'yellow']
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val(), pages => {
        //         var combineResults = posts.concat(pages);
        //         this.resultsDiv.html(`
        //      <h2 class="search-overlay__section-title">General Information</h2>
        //      ${combineResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
        //         ${combineResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
        //      ${combineResults.length ? '</ul>': ''}
        //      `);
        //      this.isSpinnerVisible = false;
        //     });
        // });
    }
    keyPressDispatcher(e) {
        console.log(this.searchField.val());
        //console.log(e.keyCode);// Get the Unicode value of the pressed keyboard
        if(e.keyCode == 83 && !this.isOverLayOpen && !$("input, textarea").is(':focus')){
            this.openOverlay();
        }
        if(e.keyCode == 27 && this.isOverLayOpen) {
            this.closeOverlay();
        }
    }
    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll"); // Doesn't let the body background scroll
        this.searchField.val(''); //Clean the search field
        setTimeout(() => this.searchField.focus(), 301); // Puts cursor in the input search field
        console.log("Open methods just Ran");
        this.isOverLayOpen = true;
        return false; //ignore default (page-search.php) behavior
    }
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        console.log("close methods just Ran");
        this.isOverLayOpen = false;
    }
    addSearchHTML() {
        $("body").append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            <div class="container">
                    <div id="search-overlay__results">
                    </div>
            </div>
        </div>
        `);
    }
}

export default Search;