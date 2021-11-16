var assignments = null;
var bookmarks = null;

function loadPage(className, formOptions){
    getAssignments(className, formOptions);
    getBookmarks(className);
}
        
function getAssignments(className, formOptions) {
    // instantiate the object
    var ajax = new XMLHttpRequest();
    // open the request
    ajax.open("GET", "assignment.php?class="+className, true);
    // ask for a specific response
    ajax.responseType = "json";
    // send the request
    ajax.send(null);
    
    // What happens if the load succeeds
    ajax.addEventListener("load", function() {
        // set question
        if (this.status == 200) { // worked 
            assignments = this.response;
            displayAssignments(className, formOptions);
            displayUpcomingAssignments();
        }
    });
    
    // What happens on error
    ajax.addEventListener("error", function() {
        document.getElementById("message").innerHTML = 
            "<div class='alert alert-danger'>An Error Occurred</div>";
    });
}

function displayUpcomingAssignments(){
    var upcomingAssignmentList = document.getElementById("upcoming-assignment-list");
    upcomingAssignmentList.innerHTML = ""
    if (assignments.length == 0){
        upcomingAssignmentList.innerHTML = `
        <div class='col-md-6 mx-auto'>
            <h4 class='text-center'>
            You currently have no assignments.
            </h4>
            <div class='text-center'>
                <a data-bs-toggle='modal' href='#assignmentsModalToggle' role='button' aria-label='Add Assignment'>
                    <button class='btn btn-primary'>Add Assignment</button>
                </a>
            </div>
        </div>`;
    }else{
        for(var i=0; i<assignments.length && i < 4; i++){
            var assignment = assignments[i];
            var due_date = new Date(assignment["due_date"]);
            var today = new Date();
            var msPerDay = 24 * 60 * 60 * 1000;   
            var diff = (due_date - today) /msPerDay; 
            var due_date_status = "bg-success";
            if(diff < 0){
                due_date_status = "bg-danger";
            }else if(diff < 5){
                due_date_status = "bg-warning";
            }
            upcomingAssignmentList.innerHTML += `
            <div class='col-md-6 ml-auto'>
                `+assignment["title"]+`
                <span class='badge `+due_date_status+` px-2'>`+assignment["due_date"]+`</span>
            </div>
            `
        }
    }
}

function displayAssignments(className, formOptions){
    var assignmentList = document.getElementById("assignment-list");
    var assignmentModals = document.getElementById("assignment-modals");
    assignmentList.innerHTML = "";
    assignmentModals.innerHTML = "";
    if (assignments.length == 0){
        assignmentList.innerHTML = `
            <li class='list-group-item bg-light'>
                <h4 class='text-center'>
                You currently have no assignments.
                </h4>
                <div class='text-center'>
                    <a data-bs-toggle='modal' href='#assignmentsModalToggle' role='button' aria-label='Add Assignment'>
                    <button class='btn btn-primary'>Add Assignment</button>
                    </a>
                </div>
            </li>`;
    }else{
        for(var i=0; i<assignments.length; i++){
            var assignment = assignments[i];
            var due_date = new Date(assignment["due_date"]);
            var today = new Date();
            var msPerDay = 24 * 60 * 60 * 1000;   
            var diff = (due_date - today) /msPerDay; 
            var due_date_status = "bg-success";
            if(diff < 0){
                due_date_status = "bg-danger";
            }else if(diff < 5){
                due_date_status = "bg-warning";
            }
            assignmentList.innerHTML += `
            <li class='list-group-item bg-light'>`+assignment["title"]+`
            <div class='assignment-icons'>
                <a href="javascript:deleteAssignment('`+className+`', `+assignment["id"]+`,\``+formOptions+`\`)">
                <span class='badge check'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' fill='currentColor'
                        class='bi bi-check-circle' viewBox='0 0 16 16'>
                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z' />
                        <path
                        d='M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z' />
                    </svg>
                </span>
                </a>
                <a data-bs-toggle='modal' href='#editAssignmentModalToggle-`+assignment["id"]+`' role='button' aria-label='Edit Assignment'>
                <span class='badge edit'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' fill='currentColor'
                    class='bi bi-pencil-square' viewBox='0 0 16 16'>
                    <path
                        d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
                    <path fill-rule='evenodd'
                        d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z' />
                    </svg>
                </span>
                </a>
            </div>
            <div>
                <span class='badge `+due_date_status+`'>`+assignment["due_date"]+`</span>
            </div>
            </li>`
        
            assignmentModals.innerHTML += `
                <div class='modal fade' id='editAssignmentModalToggle-`+assignment["id"]+`' aria-hidden='true'
                aria-labelledby='editAssignmentModalToggleLabel-`+assignment["id"]+`' tabindex='-1'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='editAssignmentModalToggleLabel-`+assignment["id"]+`'>Edit Assignment</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form method='POST' action='./class.php?class=`+className+`'>
                        <div class='modal-body'>
                        <div class='row'>
                            <div class='col-md-7'>
                            <div class='mb-3'>
                                <input type='hidden' name='assignmentID' value=`+assignment["id"]+`/>
                                <label for='assignmentNameFormLabel-`+assignment["id"]+`' class='form-label'>Assignment Name</label>
                                <input type='text' class='form-control' id='assignmentNameFormLabel-`+assignment["id"]+`' placeholder='Assignment Name'
                                name='assignmentName' value='`+assignment["title"]+`' required/>
                            </div>
                            <div class='mb-3'>
                                <label for='descriptionFormLabel-`+assignment["id"]+`' class='form-label'>Description</label>
                                <textarea class='form-control' id='descriptionFormLabel-`+assignment["id"]+`' rows='3'
                                placeholder='Description' name='assignmentDescription' value='`+assignment["Description"]+`'></textarea>
                            </div>
                            </div>
                            <div class='col-md-5'>
                            <div class='mb-3'>
                                <label for='classSelect-`+assignment["id"]+`' class='form-label'>Class</label>
                                <select name='assignmentClass' id='classSelect-`+assignment["id"]+`' class='form-select' required>
                                `+formOptions+`
                                </select>
                            </div>
                            <div class='mb-3'>
                                <label for='dateSelect-`+assignment["id"]+`' class='form-label'>Due Date</label>
                                <input type='date' name='dueDate' class='form-control' id='dateSelect-`+assignment["id"]+`' value='`+assignment["due_date"]+`'  required>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class='modal-footer'>
                        <div>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                            <button type='submit' class='btn btn-primary'>Save</button>
                        </div>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
            `;
        }
    }
}

function deleteAssignment(className, assignmentID, formOptions){
    // instantiate the object
    var ajax = new XMLHttpRequest();
    // open the request
    ajax.open("GET", "assignment.php?class="+className+"&delete_assignment="+assignmentID, true);
    // ask for a specific response
    ajax.responseType = "json";
    // send the request
    ajax.send(null);
    
    // What happens if the load succeeds
    ajax.addEventListener("load", function() {
        // set question
        if (this.status == 200) { // worked 
            assignments = this.response;
            displayAssignments(className, formOptions);
            displayUpcomingAssignments();
        }
    });
    
    // What happens on error
    ajax.addEventListener("error", function() {
        document.getElementById("message").innerHTML = 
            "<div class='alert alert-danger'>An Error Occurred</div>";
    });
}

function getBookmarks(className){
    // instantiate the object
    var ajax = new XMLHttpRequest();
    // open the request
    ajax.open("GET", "bookmark.php?class="+className, true);
    // ask for a specific response
    ajax.responseType = "json";
    // send the request
    ajax.send(null);
    
    // What happens if the load succeeds
    ajax.addEventListener("load", function() {
        // set question
        if (this.status == 200) { // worked 
            bookmarks = this.response;
            displayBookmarks(className);
        }
    });
    
    // What happens on error
    ajax.addEventListener("error", function() {
        document.getElementById("message").innerHTML = 
            "<div class='alert alert-danger'>An Error Occurred</div>";
    });
}

function displayBookmarks(className){
    var bookmarkList = document.getElementById("bookmark-list");
    bookmarkList.innerHTML = "";
    if (bookmarks.length == 0){
        bookmarkList.innerHTML = `
        <li class='list-group-item bg-light'>
            <h4 class='text-center'>
            You currently have no bookmarks.
            </h4>
            <div class='text-center'>
                <a data-bs-toggle='modal' href='#bookmarksModalToggle' role='button' aria-label='Add Bookmark'>
                    <button class='btn btn-primary'>Add bookmark</button>
                </a>
            </div>
        </li>
        `;
    }else{
        for(var i=0; i<bookmarks.length; i++){
            var bookmark = bookmarks[i];
            bookmarkList.innerHTML += `
            <li class='list-group-item bg-light'>
                `+bookmark["name"]+`
                <div class='bookmark-icons'>
                <a href='`+bookmark["url"]+`' aria-label='`+bookmark["name"]+` Link'>
                    <span class='badge link'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' fill='currentColor'
                        class='bi bi-box-arrow-up-right' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd'
                        d='M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z' />
                        <path fill-rule='evenodd'
                        d='M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z' />
                    </svg>
                    </span>
                </a>
                <a href="javascript:deleteBookmark('`+className+`',`+bookmark["id"]+`)">
                    <span class='badge trash'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' fill='currentColor' class='bi bi-trash'
                        viewBox='0 0 16 16'>
                        <path
                        d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z' />
                        <path fill-rule='evenodd'
                        d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z' />
                    </svg>
                    </span>
                </a>
                </div>
            </li>
            `;
        }
    }
}

function deleteBookmark(className, bookmarkID){
    // instantiate the object
    var ajax = new XMLHttpRequest();
    // open the request
    ajax.open("GET", "bookmark.php?class="+className+"&delete_bookmark="+bookmarkID, true);
    // ask for a specific response
    ajax.responseType = "json";
    // send the request
    ajax.send(null);
    
    // What happens if the load succeeds
    ajax.addEventListener("load", function() {
        // set question
        if (this.status == 200) { // worked 
            bookmarks = this.response;
            displayBookmarks(className);
        }
    });
    
    // What happens on error
    ajax.addEventListener("error", function() {
        document.getElementById("message").innerHTML = 
            "<div class='alert alert-danger'>An Error Occurred</div>";
    });
}