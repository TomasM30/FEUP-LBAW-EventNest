function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
      checker.addEventListener('change', sendItemUpdateRequest);
    });
  
    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
      creator.addEventListener('submit', sendCreateItemRequest);
    });
  
    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteItemRequest);
    });
  
    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteCardRequest);
    });
  
    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
      cardCreator.addEventListener('submit', sendCreateCardRequest);
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
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
  
    sendAjaxRequest('post', '/api/item/' + id, {done: checked}, itemUpdatedHandler);
  }
  
  function sendDeleteItemRequest() {
    let id = this.closest('li.item').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
  }
  
  function sendCreateItemRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    let description = this.querySelector('input[name=description]').value;
  
    if (description != '')
      sendAjaxRequest('put', '/api/cards/' + id, {description: description}, itemAddedHandler);
  
    event.preventDefault();
  }
  
  function sendDeleteCardRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
  }
  
  function sendCreateCardRequest(event) {
    let name = this.querySelector('input[name=name]').value;
  
    if (name != '')
      sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);
  
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
    form.querySelector('[type=text]').value="";
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
    let article = document.querySelector('article.card[data-id="'+ card.id + '"]');
    article.remove();
  }
  
  function cardAddedHandler() {
    if (this.status != 200) window.location = '/';
    let card = JSON.parse(this.responseText);
  
    // Create the new card
    let new_card = createCard(card);
  
    // Reset the new card input
    let form = document.querySelector('article.card form.new_card');
    form.querySelector('[type=text]').value="";
  
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

document.addEventListener('DOMContentLoaded', function() {
  let loginButton = document.getElementById('login');
  let registerButton = document.getElementById('register');
  if (loginButton) {
      loginButton.addEventListener('click', function() {
          document.getElementById('loginFormContainer').style.display = 'block';
          loginButton.style.display = 'none';
          document.getElementById('registerFormContainer').style.display = 'none';
          registerButton.style.display = 'block';
          Array.from(document.querySelectorAll('#registerFormContainer input')).forEach(function(input) {
              input.disabled = true;
          });
          Array.from(document.querySelectorAll('#loginFormContainer input')).forEach(function(input) {
              input.disabled = false;
          });
      });
  }
  if (registerButton) {
      registerButton.addEventListener('click', function() {
          document.getElementById('registerFormContainer').style.display = 'block';
          registerButton.style.display = 'none';
          document.getElementById('loginFormContainer').style.display = 'none';
          loginButton.style.display = 'block';
          Array.from(document.querySelectorAll('#loginFormContainer input')).forEach(function(input) {
              input.disabled = true;
          });
          Array.from(document.querySelectorAll('#registerFormContainer input')).forEach(function(input) {
              input.disabled = false;
          });
      });
  }
});

window.onload = function() {
  let tags = document.querySelectorAll('.tag');
  tags.forEach(function(tag) {
      let hashtags = tag.querySelectorAll('.hashtag');
      let fontSize = 20;
      if (hashtags.length > 3) {
          fontSize = 15;
      }
      tag.style.fontSize = fontSize + 'px';
  });
  let modal = document.getElementById('newEventModal');
  let btnNe = document.getElementById('NEvent-button');
  let btnEe = document.getElementById('edit-button');
  let overlay = document.getElementById('overlay');
  let dropdowns = document.getElementsByClassName("dropdown-toggle");
  let closeModalButton = document.querySelector('#newEventModal .close');
  for (let i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener('click', function() {
        let dropdownContent = this.nextElementSibling;
        dropdownContent.classList.toggle('show');
    });
}

let btn = btnEe ? btnEe : btnNe;
if (btn && modal) {
    btn.addEventListener('click', function() {
        modal.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        modal.style.overflow = 'auto';
    });
    closeModalButton.addEventListener('click', function() {
        modal.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}
};

let filteredEvents = [];
let searchTerm = '';

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
});

function filterEvents() {
  let selectedHashtags = Array.from(document.querySelectorAll('input[name="hashtags[]"]:checked')).map(input => input.value);
  let selectedPlaces = Array.from(document.querySelectorAll('input[name^="places"]:checked')).map(input => input.value);

  let url = `/events/filter`;
  let data = { hashtags: selectedHashtags, places: selectedPlaces, search: searchTerm  };

  console.log(data);
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
      document.getElementById('container').innerHTML = data.html;
      filteredEvents = data.ids;
  })
  .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
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
        let value = this.value;
        let url = document.getElementById('search-form').getAttribute('data-url');
        let data = { search: value, events: filteredEvents };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log the data object
            document.getElementById('container').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    });
}
});

function orderEventsByDate() {
  let dateButton = document.getElementById('date-button');
  let orderDirection = dateButton.getAttribute('data-direction') === 'asc' ? 'desc' : 'asc';
  let url = `/events/order`;
  dateButton.setAttribute('data-direction', orderDirection);
  dateButton.innerText = `Date ${orderDirection === 'desc' ? '↓' : '↑'}`;
  fetch(url, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ events: filteredEvents, orderBy: 'date', direction: orderDirection, search: searchTerm  })
  })
  .then(response => response.text())
  .then(data => {
      document.getElementById('container').innerHTML = data;
  })
  .catch(error => console.error('Error:', error));
}

function orderEventsByTitle() {
  let titleButton = document.getElementById('title-button');
  let orderDirection = titleButton.getAttribute('data-direction') === 'asc' ? 'desc' : 'asc';
  let url = `/events/order`;
  titleButton.setAttribute('data-direction', orderDirection);
  titleButton.innerText = `Title ${orderDirection === 'desc' ? '↓' : '↑'}`;
  fetch(url, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ events: filteredEvents, orderBy: 'title', direction: orderDirection, search: searchTerm  })
  })
  .then(response => response.text())
  .then(data => {
      document.getElementById('container').innerHTML = data;
  })
  .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('navbarToggler').addEventListener('click', function () {
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
});



