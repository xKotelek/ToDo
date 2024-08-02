function stringToBase64(string) {
    const bytes = new TextEncoder().encode(string);
    const binString = Array.from(bytes, (byte) => 
        String.fromCodePoint(byte),
    ).join("");
    return btoa(binString);
}

function Base64ToString(base64) {
    const binString = atob(base64);
    const bytes = Uint8Array.from(binString, (m) => m.codePointAt(0));
    const string = new TextDecoder().decode(bytes);
    return string;
}

function generateRandomString(length) {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    var result = '';
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function updateUserData(todosBase64, categoriesBase64) {
    var body = document.body;
    var randomString = generateRandomString(32);
    var updateForm = `<form class="updateForm" data-randomString="${randomString}" action="./api/updateUserData.inc.php" method="POST"><input type="hidden" name="todos_base64" value="${todosBase64}"><input type="hidden" name="categories_base64" value="${categoriesBase64}"></form>`;
    body.innerHTML += updateForm;
    
    var formElement = document.querySelector(`.updateForm[data-randomString="${randomString}"]`);
    formElement.submit();
}

function updateUserCategories(categoriesBase64) {
    var body = document.body;
    var randomString = generateRandomString(32);
    var updateForm = `<form class="updateForm" data-randomString="${randomString}" action="./api/updateUserCategories.inc.php" method="POST"><input type="hidden" name="categories_base64" value="${categoriesBase64}"></form>`;
    body.innerHTML += updateForm;
    
    var formElement = document.querySelector(`.updateForm[data-randomString="${randomString}"]`);
    formElement.submit();
}

function addTodo() {
    var randomString;
    var tokenExists;

    do {
        randomString = generateRandomString(32);
        var token = stringToBase64('notdone,Click to change,' + randomString);
        tokenExists = user_todos.todos.some(function(todo) {
            return todo.token.includes(token);
        });
    } while (tokenExists);

    var todoObjectJson = `{"isDone": false, "text": "Click to change", "token": "${token}", "randomString": "${randomString}"}`;
    var todoObject = JSON.parse(todoObjectJson);

    var selectedCategoryToken = selected_category.token;
    var selectedCategory = user_categories.categories.find(function(category) {
        return category.token === selectedCategoryToken;
    });

    if (selectedCategory) {
        selectedCategory.todos.push(todoObject.token);
    }

    user_todos.todos.push(todoObject);

    var userTodosJson = JSON.stringify(user_todos);
    var userCategoriesJson = JSON.stringify(user_categories);

    var todosBase64 = stringToBase64(userTodosJson);
    var categoriesBase64 = stringToBase64(userCategoriesJson);
    updateUserData(todosBase64, categoriesBase64);
}

function markTodoAsDone(dotElement) {
    var todoItem = dotElement.closest('item');
    if(todoItem) {
        var input = todoItem.querySelector('input');
        if(input) {
            input.disabled = true;
            
            user_todos.todos.forEach(function(item) {
                if (item.token == todoItem.getAttribute('data-token')) {
                    item.isDone = true;
                    item.text = input.value.trim();
                    item.token = stringToBase64('done,' + input.value.trim() + item.randomString);
                    user_categories.categories.forEach(function(category) {
                        var tokenIndex = category.todos.indexOf(todoItem.getAttribute('data-token'));
                        if (tokenIndex !== -1) {
                            category.todos[tokenIndex] = item.token;
                        }
                    });
                }
            });
            var userTodosJson = JSON.stringify(user_todos);
            var userCategoriesJson = JSON.stringify(user_categories);
        
            var todosBase64 = stringToBase64(userTodosJson);
            var categoriesBase64 = stringToBase64(userCategoriesJson);
            updateUserData(todosBase64, categoriesBase64);
        }
    }
}

function markTodoAsUndone(dotElement) {
    var todoItem = dotElement.closest('item');
    if(todoItem) {
        var input = todoItem.querySelector('input');
        if(input) {
            input.disabled = true;
            
            user_todos.todos.forEach(function(item) {
                if (item.token == todoItem.getAttribute('data-token')) {
                    item.isDone = false;
                    item.text = input.value.trim();
                    item.token = stringToBase64('notdone,' + input.value.trim() + item.randomString);
                    user_categories.categories.forEach(function(category) {
                        var tokenIndex = category.todos.indexOf(todoItem.getAttribute('data-token'));
                        if (tokenIndex !== -1) {
                            category.todos[tokenIndex] = item.token;
                        }
                    });
                }
            });
            var userTodosJson = JSON.stringify(user_todos);
            var userCategoriesJson = JSON.stringify(user_categories);
        
            var todosBase64 = stringToBase64(userTodosJson);
            var categoriesBase64 = stringToBase64(userCategoriesJson);
            updateUserData(todosBase64, categoriesBase64);
        }
    }
}

function removeTodo(completedElement) {
    var todoItem = completedElement.closest('item');
    if (todoItem) {
        var input = todoItem.querySelector('input');
        if (input) {
            input.disabled = true;

            var tokenToRemove = todoItem.getAttribute('data-token');
            
            user_categories.categories.forEach(function(category) {
                var tokenIndex = category.todos.indexOf(tokenToRemove);
                if (tokenIndex !== -1) {
                    category.todos.splice(tokenIndex, 1); 
                }
            });
            
            user_todos.todos = user_todos.todos.filter(function(item) {
                return item.token !== tokenToRemove;
            });

            var userTodosJson = JSON.stringify(user_todos);
            var userCategoriesJson = JSON.stringify(user_categories);
        
            var todosBase64 = stringToBase64(userTodosJson);
            var categoriesBase64 = stringToBase64(userCategoriesJson);
            updateUserData(todosBase64, categoriesBase64);
        }
    }
}

function editTodoText(dotElement) {
    var todoItem = dotElement.closest('item');
    if (todoItem) {
        var input = todoItem.querySelector('input');
        if (input) {
            input.disabled = false;
            input.focus();
            if (!input._hasBlurListener) {
                input._hasBlurListener = true;
                input.addEventListener('blur', function() {
                    input.disabled = true;
                    user_todos.todos.forEach(function(item) {
                        if (item.token == todoItem.getAttribute('data-token')) {
                            item.text = input.value.trim();
                            item.token = stringToBase64('notdone,' + input.value.trim() + item.randomString);
                            user_categories.categories.forEach(function(category) {
                                console.log(JSON.stringify(category));
                                var tokenIndex = category.todos.indexOf(todoItem.getAttribute('data-token'));
                                if (tokenIndex !== -1) {
                                    category.todos[tokenIndex] = item.token;
                                }
                            });
                        }
                    });

                    var userTodosJson = JSON.stringify(user_todos);
                    var userCategoriesJson = JSON.stringify(user_categories);
                
                    var todosBase64 = stringToBase64(userTodosJson);
                    var categoriesBase64 = stringToBase64(userCategoriesJson);
                    updateUserData(todosBase64, categoriesBase64);
                });
            }
        }
    }
}

function addCategory(plusElement) {
    var randomString;
    var tokenExists;

    do {
        randomString = generateRandomString(32);
        var token = stringToBase64('notdone,Click to change,' + randomString);
        tokenExists = user_todos.todos.some(function(todo) {
            return todo.token.includes(token);
        });
    } while (tokenExists);

    var categoryObjectJson = `{"name": "Example Category", "token": "${token}", "randomString": "${randomString}", "todos": []}`;
    var categoryObject = JSON.parse(categoryObjectJson);

    user_categories.categories.push(categoryObject);
    selected_category = categoryObject;
    localStorage.setItem('selected_category', JSON.stringify(selected_category));

    var userTodosJson = JSON.stringify(user_todos);
    var userCategoriesJson = JSON.stringify(user_categories);

    var todosBase64 = stringToBase64(userTodosJson);
    var categoriesBase64 = stringToBase64(userCategoriesJson);
    updateUserData(todosBase64, categoriesBase64);
};

function selectCategoryByToken(categoryToken) {
    var selectedCategory = user_categories.categories.find(function(category) {
        return category.token === categoryToken;
    });

    if (selectedCategory) {
        selected_category = selectedCategory;
        localStorage.setItem('selected_category', JSON.stringify(selected_category));

        var allCategoryDivs = document.querySelectorAll('elementf');
        allCategoryDivs.forEach(function(categoryElement) {
            categoryElement.classList.remove('selected');
            if (categoryElement.getAttribute('data-token') === categoryToken) {
                categoryElement.classList.add('selected');
            }
        });

        addExistingTodos();
    }
}

function displayCategory(categoryElement) {
    var categoryToken = categoryElement.getAttribute('data-token');
    selectCategoryByToken(categoryToken);

    var inputElement = categoryElement.querySelector('input');
    if (inputElement && !inputElement.disabled && document.activeElement === inputElement) {
        inputElement.focus();
        inputElement.addEventListener('blur', function() {
            user_categories.categories.forEach(function(item) {
                if (item.token == categoryToken) {
                    item.name = inputElement.value.trim();
                    item.token = stringToBase64(inputElement.value.trim() + ',' + item.randomString);
                    console.log(item.token);
                }
            });

            var userCategoriesJson = JSON.stringify(user_categories);
            var categoriesBase64 = stringToBase64(userCategoriesJson);
            updateUserCategories(categoriesBase64);
        });
    }
}

function adjustCategoryInputWidth(inputElement) {
    var tempSpan = document.createElement('span');
    tempSpan.style.visibility = 'hidden';
    tempSpan.style.position = 'absolute';
    tempSpan.style.whiteSpace = 'nowrap';
    tempSpan.style.fontWeight = '700';
    tempSpan.style.fontFamily = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif';
    
    document.body.appendChild(tempSpan);

    tempSpan.innerText = inputElement.value || inputElement.placeholder;

    inputElement.style.width = tempSpan.clientWidth - (tempSpan.clientWidth / 7) + 'px';

    document.body.removeChild(tempSpan);
}

function addExistingTodos() {
    var todosDiv = document.querySelector('.items');
    var selectedCategoryTokens = selected_category.todos;

    todosDiv.innerHTML = "";

    user_todos.todos.forEach(todo => {
        var isDone = todo.isDone;
        var text = todo.text;
        var token = todo.token;
        var html;

        if (selectedCategoryTokens.includes(token)) {
            if (isDone) {
                html = `<item class="done" data-token="${token}"><dot onclick="markTodoAsUndone(this);">←</dot><input type="text" style="overflow-y: hidden;" value="${text}"></input><completed onclick="removeTodo(this);"><p>🗑</p></completed></item>`;
            } else {
                html = `<item data-token="${token}"><dot onclick="editTodoText(this);">✎</dot><input type="text" style="overflow-y: hidden;" value="${text}" disabled></input><completed onclick="markTodoAsDone(this);"><p>✔</p></completed></item>`;
            }

            todosDiv.innerHTML += html;
        }
    });
}

function addExistingCategories() {
    var categoriesDiv = document.querySelector('.categories');

    categoriesDiv.innerHTML = "";

    user_categories.categories.forEach((category, index) => {
        var name = category.name;
        var token = category.token;
        var html;

        if(selected_category == false) {
            var selectedClass = index === 0 ? ' class="selected"' : '';
            if(index === 0) {
                selected_category = category;
                localStorage.setItem('selected_category', JSON.stringify(selected_category));
            }
        } else {
            if(selected_category.token == token) {
                selectedClass = ' class="selected"';
            } else {
                selectedClass = '';
            }
        }

        html = `<elementf data-token="${token}" onclick="displayCategory(this);"${selectedClass}><t><input type="text" style="overflow-y:hidden;" value="${name}"><t></elementf>`;

        categoriesDiv.innerHTML += html;
    });

    var inputs = categoriesDiv.querySelectorAll('input');
    inputs.forEach(input => adjustCategoryInputWidth(input));
}

window.addEventListener('load', function() {
    if(!this.localStorage.getItem('selected_category')) {
        selected_category = false;
    } else {
        selected_category = JSON.parse(localStorage.getItem('selected_category'));
    }
    addExistingCategories();
    if(selected_category) {
        selectCategoryByToken(selected_category.token);
    } else {
        addExistingTodos();
    }
});
