// login.page.js
var Page = require('./page')

var IwPage = Object.create(Page, {
    /**
     * define elements
     */
    form: {
        get: function () {
            return browser.element('#answerForm');
        }
    },
    startInterviewLink: {
        get: function () {
            return browser.element('=Start new interview');
        }
    },
    nextButton: {
        get: function () {
            return browser.element('button.orangebutton=Next');
        }
    },
    backButton: {
        get: function () {
            return browser.element('button.graybutton=Back');
        }
    },
    dkLabel: {
        get: function () {
            return browser.element('label=Don\'t Know')
        }
    },
    rfLabel: {
        get: function () {
            return browser.element('label=Refuse')
        }
    },
    questionTitle: {
        get: function () {
            return browser.element('span#questionTitle')
        }
    },
    alterTextBox: {
        get: function () {
            return browser.element("form#alterForm div autocomplete div input");
        }
    },
    alterAddButton: {
        get: function () {
            return browser.element('input.alterSubmit');
        }
    },

    // EGO ID of current case in survey
    ewid: {
        value: 0,
        enumerable: false,
        writable: true,
        configurable: false
    },

    // fake data to use in survey questions
    fieldValues: {
        value: {},
        enumerable: false,
        writable: true,
        configurable: false
    },

    /**
     * define or overwrite page methods
     */
    inputField: {
        value: function (id = null) {
            if (id != null) {
                return browser.element('input#Answer_' + id + '_VALUE');
            } else {
                return browser.element('input[name*="Answer"]');
            }
        }
    },

    specificQuestionTitle: {
        value: function (question) {
            return browser.element('span#questionTitle=' + question);
        }
    },

    goBackToQuestion: {
        value: function (question) {
            // keep hitting "back" button until we get to the question
            // TODO - put some "maximum" in this loop, and fail if it doesn't find the question
            while ((foo = IwPage.questionTitle.getText()) != question) {
                this.back();
            }
            this.pause();
        }
    },

    getOptionSelector: {
        value: function(num) {
            // add 1 to num because of hidden row with labels for ego/alter/alter_pair
            return "form#answerForm>div>div.panel>div:nth-child(" + (parseInt(num) + 1) + ")>input.answerInput";
        }
    },

    getOptionSpecifySelector: {
        value: function(num) {
            // add 1 to num because of hidden row with labels for ego/alter/alter_pair
            // use 3rd child because of checkbox, label, then textbox
            return "form#answerForm>div>div.panel>div:nth-child(" + (parseInt(num) + 1) + ")>input:nth-child(3)";
        }
    },

    selectOption: {
        value: function (num) {
            let selector = this.getOptionSelector(num);
            if (!browser.element(selector).isSelected()) {
                browser.element(selector).click();
            }
        }
    },

    unselectOption: {
        value: function (num) {
            let selector = this.getOptionSelector(num);
            if (browser.element(selector).isSelected()) {
                browser.element(selector).click();
            }
        }
    },

    unselectAllOptions: {
        value: function(options) {
            for (var key in options) {
                if (!options.hasOwnProperty(key)) continue;
                this.unselectOption(key);
            }
        }
    },

    optionLabel: {
        value: function (label) {
            return browser.element('label=' + label);
        }
    },

    goForwardToQuestion: {
        value: function (question) {
            // keep hitting "next" button until we get to the question
            // TODO - put some "maximum" in this loop, and fail if it doesn't find the question
            while ((curfield = IwPage.questionTitle.getText()) != question) {
                if (curfield in this.fieldValues) {
                    let fv = this.fieldValues[curfield];
                    switch (fv.type) {
                        case 'input':
                            // simple text filed
                            this.inputField(fv.field).setValue(fv.value);
                            break;
                        case 'ms':
                            for (var key in fv.options) {
                                if (!fv.options.hasOwnProperty(key)) continue;
                                if (fv.options[key]) {
                                    this.selectOption(key);
                                } else {
                                    this.unselectOption(key);
                                }
                            }
                            break;
                        case 'alters':
                            this.removeAllAlters();
<<<<<<< HEAD
                            for (let i=0;i<fv.values.length;i++) {
=======
                            for (i=0;i<fv.values.length;i++) {
>>>>>>> dev
                                this.addAlter(fv.values[i]);
                            }
                            break;
                    }
                }
                this.next();
            }
        }
    },

    next: {
        value: function () {
            this.nextButton.waitForVisible(browser.options.egoweb.waitTime);
            this.nextButton.click();
            this.pause();
        }
    },

    back: {
        value: function () {
            this.backButton.waitForVisible(browser.options.egoweb.waitTime);
            this.backButton.click();
            this.pause();
        }
    },

    pause: {
        value: function () {
            browser.pause(browser.options.egoweb.pauseTime);
        }
    },

    addAlter: {
        value: function(name) {
            this.alterTextBox.setValue(name);
<<<<<<< HEAD
            this.pause();
=======
>>>>>>> dev
            this.alterAddButton.click();
            this.pause();
        }
    },

    removeNthAlterButton: {
        value: function(num) {
            return browser.element("div#alterListBox>table.items>tbody>tr:nth-child("+parseInt(num)+")>td>a.alter-delete");
        }
    },

    removeNthAlter: {
        value: function(num) {
            this.removeNthAlterButton(num).click();
            this.pause();
        }
    },

    removeAllAlters: {
        value: function() {
            let btn = this.removeNthAlterButton(1);

            while (btn.isExisting()) {
                btn.click();
                this.pause();
                btn = this.removeNthAlterButton(1);
            }
        }
    },

    getAlterCount: {
        value: function() {
            let alters = browser.elements("div#alterListBox>table.items>tbody>tr");
            return alters.value.length;
        }
    },

    getTableCellSelector: {
        value: function(row, col) {
            return "form#answerForm>div.multiBox>div.multi:nth-child("+parseInt(row)+")>div:nth-child("+parseInt(col)+")";
        }
    },

    getTableRowSelector: {
        value: function(row) {
            return "form#answerForm>div.multiBox>div.multi:nth-child("+parseInt(row)+")";
        }
    },

    getTableHeaderText: {
        value: function(col) {
            let selector = this.getTableCellSelector(1,col);
            return browser.element(selector).getText();
        }
    },

    getTableCellInputElement: {
        value: function(row,col) {
            let selector = this.getTableCellSelector(row,col);
            selector += ">input";
            return browser.element(selector);
        }
    },

    getTableRowHighlight: {
        value: function(row) {
            let selector = this.getTableCellSelector(row, 1);
            let el = browser.element(selector);
            let foo = el.getAttribute("class");
            return (foo.indexOf("bg-danger") !== -1);
        }
    },

    openInterview: {
        value: function (interview) {
            this.open('interview');

            browser.element("=" + interview).click();

            // search existing IDs to find max
            browser.element("table.items").waitForExist(browser.options.egoweb.waitTime);
            ids = browser.elements("table.items tr td");

            max = 0;
            ids.value.forEach(function (el) {
                val = parseInt(browser.elementIdText(el.ELEMENT).value);
                if (val > max) {
                    max = val;
                }
            });

            // pick a random ID higher than max, in case multiple tests/threads are running simultaneously
            newid = max + 1 + Math.floor(Math.random() * 100);

            // save ID
            this.ewid = newid;

            // start interview
            this.startInterviewLink.click();

            return this;
        }
    },

    submit: {
        value: function () {
            this.form.submitForm();
            return this;
        }
    }
});

module.exports = IwPage;