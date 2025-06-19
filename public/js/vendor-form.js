
function addField(wrapperId, fieldName, placeholderText) {
    const wrapper = document.getElementById(wrapperId);

    const input = document.createElement('input');
    input.type = 'text';
    input.name = fieldName;
    input.placeholder = placeholderText;
    input.classList.add('form-control', 'mb-1');

    wrapper.appendChild(input);
}
