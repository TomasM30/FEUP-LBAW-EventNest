function addEventListeners() {
  let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
  [].forEach.call(itemCheckers, function (checker) {
    checker.addEventListener('change', sendItemUpdateRequest);
  });

  let itemCreators = document.querySelectorAll('article.card form.new_item');
  [].forEach.call(itemCreators, function (creator) {
    creator.addEventListener('submit', sendCreateItemRequest);
  });

  let itemDeleters = document.querySelectorAll('article.card li a.delete');
  [].forEach.call(itemDeleters, function (deleter) {
    deleter.addEventListener('click', sendDeleteItemRequest);
  });

  let cardDeleters = document.querySelectorAll('article.card header a.delete');
  [].forEach.call(cardDeleters, function (deleter) {
    deleter.addEventListener('click', sendDeleteCardRequest);
  });

  let cardCreator = document.querySelector('article.card form.new_card');
  if (cardCreator != null)
    cardCreator.addEventListener('submit', sendCreateCardRequest);
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function sendItemUpdateRequest() {
  let item = this.closest('li.item');
  let id = item.getAttribute('data-id');
  let checked = item.querySelector('input[type=checkbox]').checked;

  sendAjaxRequest('post', '/api/item/' + id, { done: checked }, itemUpdatedHandler);
}

function sendDeleteItemRequest() {
  let id = this.closest('li.item').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
}

function sendCreateItemRequest(event) {
  let id = this.closest('article').getAttribute('data-id');
  let description = this.querySelector('input[name=description]').value;

  if (description != '')
    sendAjaxRequest('put', '/api/cards/' + id, { description: description }, itemAddedHandler);

  event.preventDefault();
}

function sendDeleteCardRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
}

function sendCreateCardRequest(event) {
  let name = this.querySelector('input[name=name]').value;

  if (name != '')
    sendAjaxRequest('put', '/api/cards/', { name: name }, cardAddedHandler);

  event.preventDefault();
}

function itemUpdatedHandler() {
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  let input = element.querySelector('input[type=checkbox]');
  element.checked = item.done == "true";
}

function itemAddedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);

  // Create the new item
  let new_item = createItem(item);

  // Insert the new item
  let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
  let form = card.querySelector('form.new_item');
  form.previousElementSibling.append(new_item);

  // Reset the new item form
  form.querySelector('[type=text]').value = "";
}

function itemDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  element.remove();
}

function cardDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);
  let article = document.querySelector('article.card[data-id="' + card.id + '"]');
  article.remove();
}

function cardAddedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);

  // Create the new card
  let new_card = createCard(card);

  // Reset the new card input
  let form = document.querySelector('article.card form.new_card');
  form.querySelector('[type=text]').value = "";

  // Insert the new card
  let article = form.parentElement;
  let section = article.parentElement;
  section.insertBefore(new_card, article);

  // Focus on adding an item to the new card
  new_card.querySelector('[type=text]').focus();
}

function createCard(card) {
  let new_card = document.createElement('article');
  new_card.classList.add('card');
  new_card.setAttribute('data-id', card.id);
  new_card.innerHTML = `
  
    <header>
      <h2><a href="cards/${card.id}">${card.name}</a></h2>
      <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_item">
      <input name="description" type="text">
    </form>`;

  let creator = new_card.querySelector('form.new_item');
  creator.addEventListener('submit', sendCreateItemRequest);

  let deleter = new_card.querySelector('header a.delete');
  deleter.addEventListener('click', sendDeleteCardRequest);

  return new_card;
}

function createItem(item) {
  let new_item = document.createElement('li');
  new_item.classList.add('item');
  new_item.setAttribute('data-id', item.id);
  new_item.innerHTML = `
    <label>
      <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
    </label>
    `;

  new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
  new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);

  return new_item;
}

addEventListeners();

document.addEventListener('DOMContentLoaded', function () {
  let loginButton = document.getElementById('login');
  let registerButton = document.getElementById('register');
  if (loginButton) {
    loginButton.addEventListener('click', function () {
      document.getElementById('loginFormContainer').style.display = 'block';
      loginButton.style.display = 'none';
      document.getElementById('registerFormContainer').style.display = 'none';
      registerButton.style.display = 'block';
      Array.from(document.querySelectorAll('#registerFormContainer input')).forEach(function (input) {
        input.disabled = true;
      });
      Array.from(document.querySelectorAll('#loginFormContainer input')).forEach(function (input) {
        input.disabled = false;
      });
    });
  }
  if (registerButton) {
    registerButton.addEventListener('click', function () {
      document.getElementById('registerFormContainer').style.display = 'block';
      registerButton.style.display = 'none';
      document.getElementById('loginFormContainer').style.display = 'none';
      loginButton.style.display = 'block';
      Array.from(document.querySelectorAll('#loginFormContainer input')).forEach(function (input) {
        input.disabled = true;
      });
      Array.from(document.querySelectorAll('#registerFormContainer input')).forEach(function (input) {
        input.disabled = false;
      });
    });
  }
});

window.onload = function () {
  let tableResponsive = document.querySelector('.table-responsive');

  if (tableResponsive) {
    tableResponsive.addEventListener('click', function (e) {
      let paginationLink = e.target.closest('.pagination a');
  
      if (paginationLink) {
        e.preventDefault();
  
        fetch(paginationLink.href, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => response.text())
          .then(html => {
            tableResponsive.innerHTML = html;
          });
      }
    });
  }

  let tableResponsive2 = document.querySelector('.table-responsive');

  if (tableResponsive2) {
    tableResponsive.addEventListener('click', function (e) {
      let paginationLink = e.target.closest('.pagination a');
  
      if (paginationLink) {
        e.preventDefault();
  
        fetch(paginationLink.href, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => response.text())
          .then(html => {
            tableResponsive.innerHTML = html;
          });
      }
    });
  }


  let acceptTerms = document.getElementById('acceptTerms');
  let confirmCertainty = document.getElementById('confirmCertainty');
  let submitButton = document.getElementById('submitButton');

  if (acceptTerms && confirmCertainty && submitButton) {
    acceptTerms.addEventListener('change', checkConditions);
    confirmCertainty.addEventListener('change', checkConditions);

    function checkConditions() {
      submitButton.disabled = !(acceptTerms.checked && confirmCertainty.checked);
    }
  }

  let activeReport = document.querySelector('.list-group-item.active');
  if (activeReport) {
    document.getElementById('reportList').scrollTop = activeReport.offsetTop;
  }

  let dropdowns = document.getElementsByClassName("dropdown-toggle");
  for (let i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener('click', function () {
      let dropdownContent = this.nextElementSibling;
      dropdownContent.classList.toggle('show');
    });
  }

  function handleModal(modalId, triggerId, overlayId) {
    let modal = document.getElementById(modalId);
    let trigger = document.getElementById(triggerId);
    let overlay = document.getElementById(overlayId);

    if (!modal || !trigger || !overlay) {
      console.error('One or more elements could not be found.');
      return;
    }

    let closeModalButton = modal.querySelector('.close');

    trigger.addEventListener('click', function () {
      modal.style.display = 'block';
      overlay.style.display = 'block';
      document.body.style.overflow = 'hidden';
    });

    if (closeModalButton) {
      closeModalButton.addEventListener('click', function () {
        modal.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
      });
    }

    window.addEventListener('click', function (event) {
      if (event.target == modal) {
        modal.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
      }
    });
  }

  handleModal('newEventModal', 'NEvent-button', 'overlay');
  handleModal('uploadModal', 'editProfileButton', 'overlay');
  handleModal('newEventModal', 'edit-button', 'overlay');
  handleModal('passwordChangeModal', 'changePasswordBtn', 'overlay');
  handleModal('deleteAccountModal', 'deleteAccountBtn', 'overlay');
  handleModal('reportModal', 'reportBtn', 'overlay');
  handleModal('verificationModal', 'verifiedBTn', 'overlay');
  handleModal('verificationModal', 'questionBtn', 'overlay');
  handleModal('tagModal', 'tagBtn', 'overlay');

};


document.addEventListener('DOMContentLoaded', function () {
  let navbarToggler = document.getElementById('navbarToggler');

  if (navbarToggler) {
    navbarToggler.addEventListener('click', function () {
      let navbarCollapse = document.getElementById('navbarColor01');
      if (navbarCollapse.classList.contains('show')) {
        navbarCollapse.classList.remove('show');
        navbarCollapse.classList.add('collapsing');
        setTimeout(function () {
          navbarCollapse.classList.remove('collapsing');
          navbarCollapse.style = '';
        }, 350);
      } else {
        navbarCollapse.classList.remove('collapsing');
        navbarCollapse.classList.add('show');
        navbarCollapse.style = 'display: block;';
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('#v-pills-tab .nav-link:not(.no-js)').forEach(function (tab) {
    tab.addEventListener('click', function (e) {
      e.preventDefault();

      document.querySelectorAll('#v-pills-tab .nav-link:not(.no-js)').forEach(function (tab) {
        tab.classList.remove('active');
      });

      document.querySelectorAll('.tab-pane').forEach(function (tabContent) {
        tabContent.classList.remove('show', 'active');
      });

      tab.classList.add('active');
      document.querySelector(tab.getAttribute('href')).classList.add('show', 'active');
    });
  });
});




let filteredEvents = [];
let searchTerm = '';
let orderBy = 'date';
let direction = 'asc';

document.addEventListener('DOMContentLoaded', function() {
  let acc = document.getElementsByClassName("accordion-button");
  for (let i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
          this.classList.toggle("active");
          let panel = this.parentNode.nextElementSibling;
          if (panel.style.display === "block") {
              panel.style.display = "none";
          } else {
              panel.style.display = "block";
          }
      });
  }

  Array.from(document.querySelectorAll('input[name="hashtags[]"], input[name^="places"]')).forEach(function(checkbox) {
    checkbox.addEventListener('change', fetchEvents);
  });

  let searchForm = document.getElementById('search-form');
  if(searchForm) {
      searchForm.addEventListener('submit', function(event) {
          event.preventDefault();
      });
  }

  let form1 = document.getElementById('form1');
  if(form1) {
    form1.addEventListener('keyup', function() {
        searchTerm = this.value;
        fetchEvents();
    });
  }

  let dateButton = document.getElementById('date-button');
  if(dateButton) {
    dateButton.addEventListener('click', function() {
      orderEvents('date');
    });
  }

  let titleButton = document.getElementById('title-button');
  if(titleButton) {
    titleButton.addEventListener('click', function() {
        orderEvents('title');
    });
  }

  fetchEvents();
});

function fetchEvents(page = 1) {
  let selectedHashtags = Array.from(document.querySelectorAll('input[name="hashtags[]"]:checked')).map(input => input.value);
  let selectedPlaces = Array.from(document.querySelectorAll('input[name^="places"]:checked')).map(input => input.value);
  let typeElement = document.querySelector('input[name="type"]');
  let type = typeElement ? typeElement.value : null;

  let url = `/events/filter`;
  let data = { 
    hashtags: selectedHashtags, 
    places: selectedPlaces, 
    search: searchTerm,
    orderBy: orderBy,
    direction: direction,
    type: type,
    page: page // Use the page parameter
  };

  fetch(url, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(data => {
    let container = document.getElementById('container');
    if(container) {
        container.innerHTML = data.html;
    }
    setActivePageNumber(page); // Set the active page number
    filteredEvents = data.ids;
    window.scrollTo(0, 0);
  })
  .catch(error => console.error('Error:', error));
}

document.addEventListener('click', function(event) {
  if (event.target.matches('.pagination a')) {
    event.preventDefault();
    let page = event.target.getAttribute('href').split('page=')[1];
    fetchEvents(page);
  }
});

function orderEvents(orderByField) {
  orderBy = orderByField;
  direction = direction === 'asc' ? 'desc' : 'asc';

  let dateButton = document.getElementById('date-button');
  let titleButton = document.getElementById('title-button');
  if(orderBy === 'date') {
    dateButton.innerHTML = direction === 'asc' ? 'Newest First <i class="fas fa-arrow-down"></i>' : 'Oldest First <i class="fas fa-arrow-up"></i>';
    titleButton.innerHTML = 'Title';
  } else {
    titleButton.innerHTML = direction === 'asc' ? 'Z-A <i class="fas fa-arrow-down"></i>' : 'A-Z <i class="fas fa-arrow-up"></i>';
    dateButton.innerHTML = 'Date';
  }

  fetchEvents();
}

function setActivePageNumber(page) {
  let activePageNumber = document.querySelector('.pagination .active');
  if (activePageNumber) {
    activePageNumber.classList.remove('active');
  }

  let newActivePageNumber;
  let element;
  if (page === 1) {
    element = Array.from(document.querySelectorAll('.pagination a:not([href*="page="])')).find(el => el.textContent.trim() === '1');
  } else {
    element = document.querySelector(`.pagination a[href*="page=${page}"]`);
  }
  
  if (element) {
    newActivePageNumber = element.parentNode;
  }
  
  if (newActivePageNumber) {
    newActivePageNumber.classList.add('active');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  let searchInput = document.getElementById('adminsearch');
  let searchForm = document.getElementById('search-form');
  if(searchInput) {
    searchInput.addEventListener('keyup', function() {
      let search = this.value;
      let url = searchForm.getAttribute('data-url') + '?search=' + encodeURIComponent(search);
      fetch(url)
        .then(response => response.text())
        .then(data => {
          let tableClass = searchForm.getAttribute('data-url').includes('users') ? 'usersTable' : 'eventsTable';
          document.querySelector('.' + tableClass).innerHTML = data;
        });
    });
  }
});

function fetchSearchResults(search, page = 1) {
  let searchForm = document.getElementById('search-form');
  let url = searchForm.getAttribute('data-url') + '?search=' + encodeURIComponent(search) + '&page=' + page;
  fetch(url)
    .then(response => response.text())
    .then(data => {
      let tableClass = searchForm.getAttribute('data-url').includes('users') ? 'usersTable' : 'eventsTable';
      let table = document.querySelector('.' + tableClass);
      table.innerHTML = data;
      // Add 'active' class to the correct pagination link
      let activePageLink = table.querySelector(`.pagination a[href*="page=${page}"]`);
      if (activePageLink) {
        activePageLink.parentNode.classList.add('active');
      }
    });
}

document.addEventListener('DOMContentLoaded', function() {
  let searchInput = document.getElementById('adminsearch');
  if(searchInput) {
    searchInput.addEventListener('keyup', function() {
      fetchSearchResults(this.value);
    });
  }

  document.addEventListener('click', function(event) {
    if (event.target.matches('.pagination a')) {
      event.preventDefault();
      let page = parseInt(event.target.getAttribute('href').split('page=')[1]);
      fetchSearchResults(searchInput.value, page);
    }
  });
});



document.addEventListener('DOMContentLoaded', function() {

  
  let pusher = new Pusher('57eb230bb0a09d49e0ae', {
      cluster: 'eu',
      encrypted: true
  });

  pusher.connection.bind('connected', function() {
    console.log('Connected to Pusher!');
  });

  let chatRoomId = document.getElementById('v-pills-chat').dataset.eventId;
  let channel = pusher.subscribe('chat-room-' + chatRoomId);

  channel.bind('App\\Events\\MessageSent', function(data) {
    let chat = document.getElementById('chat');
    let message = document.createElement('p');
    message.textContent = data.message.content;
    console.log(data.message.content);
    chat.appendChild(message);
  });

  let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  let form = document.getElementById('message-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();
    let messageInput = document.getElementById('message-input');
    fetch('/send-message', {
        method: 'POST',
        body: JSON.stringify({
            content: messageInput.value,
            id_event: chatRoomId
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(function(response) {
        if (response.ok) {
            messageInput.value = '';
        }
    });
  });
});