let productElement = document.querySelector('.Product_LTR');

if (productElement) {

    let children = productElement.children;

    for (let i = 0; i < children.length; i++) {
        if (i % 2 === 0) {
            children[i].setAttribute('dir', 'ltr');
            children[i].children[0].children[1].setAttribute('dir', 'rtl');
        }
    }
}