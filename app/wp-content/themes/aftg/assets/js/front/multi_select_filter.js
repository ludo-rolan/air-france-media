
class CustomSelect {
    constructor(originalSelect) {
        this.select_index = originalSelect.getAttribute('select_index');
        this.originalSelect = originalSelect;
        this.customSelect = document.getElementsByClassName("filter_"+this.select_index)[0];

        this.originalSelect.querySelectorAll("option").forEach((optionElement) => {
            const itemElement = document.getElementsByClassName("filter_item_"+this.select_index+" "+ optionElement.value)[0];
            itemElement.addEventListener("click", () => {
                this._toggle(itemElement);
            });
        });
    }

    _toggle(itemElement) {
        const index = Array.from(this.customSelect.children).indexOf(itemElement);
        let itemImg = itemElement.getElementsByClassName("multi_select_filter_item_img");
        let numberIcon = itemElement.getElementsByClassName("multi_select_filter_numbers");
        let is_selected = this.originalSelect.querySelectorAll("option")[index].selected;
        
        if (is_selected) {
            this.originalSelect.querySelectorAll("option")[index].selected = false;
            this.originalSelect.querySelectorAll("option")[index].removeAttribute('selected');
        } else {
            this.originalSelect.querySelectorAll("option")[index].selected = true;
            this.originalSelect.querySelectorAll("option")[index].setAttribute('selected', 'true');
        }
        itemElement.classList.toggle("multi_select_filter_item--selected");

        if (itemImg.length) {
            itemImg[0].classList.toggle("multi_select_filter_item_img--selected");
        }

        if (numberIcon.length) {
            numberIcon[0].classList.toggle("multi_select_filter_numbers--selected");
        }
    }
}

document.querySelectorAll(".filter_hidden").forEach((selectElement) => {
    return new CustomSelect(selectElement);
});
