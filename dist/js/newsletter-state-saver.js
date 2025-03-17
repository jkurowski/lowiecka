export class NewsletterStateSaver {
    /**
     * @param {NewsletterStateCollector} collector
     */
    constructor(collector) {
        this.initialState = {
            users: [],
            action: "create_template",
            subject: "",
            template: "",
            content: "",
            "schedule-send": "",
            "test-email": "",
            checkboxes: {
                "schedule-send": false,
                "send-test-email": false,
            },
        };

        this.collector = collector;
        this.loadFromStorage();
        this.collector.applyToDom(this.state);
        this.saveOnUnload();
    }
    setState(state) {
        this.state = state;
    }
    clearState() {
        this.state = Object.assign({}, this.initialState);
    }
    saveToStorage() {
        localStorage.setItem(
            "newsletterState",
            JSON.stringify(this.collector.getState())
        );
    }
    getShouldClearFlag() {
        return localStorage.getItem('shouldClear') === '1';
        // return false;
    }
    clearShouldClearFlag() {
        localStorage.removeItem('shouldClear');
    }

    loadFromStorage() {
        const savedState = localStorage.getItem("newsletterState");
        if (savedState && !this.getShouldClearFlag()) {
            this.state = JSON.parse(savedState);
        } else {
            this.state = Object.assign({}, this.initialState);
        }
        this.clearShouldClearFlag();
    }

    isInStorage() {
        return localStorage.getItem("newsletterState") !== null;
    }

    saveOnUnload() {
        window.addEventListener("beforeunload", () => {
            this.collector.collect();
            this.saveToStorage();
        });
    }
}

export class NewsletterStateCollector {
    constructor(tinymce) {
        this.tinymce = tinymce;
        this.state = {};

        this.collapsedCheckboxes = {
            "schedule-send": false,
            "send-test-email": false,
        };

        this.collect();
    }
    getState() {
        return {
            ...this.state,
            checkboxes: this.collapsedCheckboxes,
        };
    }

    collect() {
        this.collectUsers();
        this.collectAction();
        this.collectSubject();
        this.collectTemplate();
        this.collectContent();
        this.collectScheduleSend();
        this.collectTestEmail();
        this.collectAction();
        this.collectCollapsedCheckboxes();
    }



    collectCollapsedCheckboxes() {
        this.collapsedCheckboxes["schedule-send"] =
            document.getElementById("schedule-send").checked;
        this.collapsedCheckboxes["send-test-email"] =
            document.getElementById("send-test-email").checked;
    }

    collectUsers() {
        const checkboxes = document.querySelectorAll(
            "#clients-table input[type='checkbox']:checked"
        );
        const usersIds = Array.from(checkboxes).map(
            (checkbox) => checkbox.value
        );
        this.state.users = usersIds;
    }

    collectAction() {
        this.state.action = document.querySelector(
            "[name='action']:checked"
        ).value;
    }

    collectSubject() {
        this.state.subject = document.querySelector("[name='subject']").value;
    }

    collectTemplate() {
        this.state.template = document.querySelector("[name='template']").value;
    }

    collectContent() {
        if (this.tinymce.activeEditor) {
            this.state.content = this.tinymce.activeEditor.getContent();
        } else {
            this.state.content = "";
        }
    }

    collectScheduleSend() {
        this.state["schedule-send"] = document.querySelector(
            "[name='schedule-send']"
        ).value;
    }

    collectTestEmail() {
        this.state["test-email"] = document.getElementById("test-email").value;
    }

    applyToDom(state) {
        this.applyAction(state.action);
        this.applySubject(state.subject);
        this.applyTemplate(state.template);
        this.applyScheduleSend(state["schedule-send"]);
        this.applyTestEmail(state["test-email"]);
        this.applyContent(state.content);
        this.applyUsers(state.users);
        this.applyCollapsedCheckboxes(state.checkboxes);
    }

    applyAction(action) {
        document.querySelectorAll("[name='action']").forEach((element) => {
            if (element.value === action) {
               element.dispatchEvent(new Event("click", { bubbles: true }));
               element.checked = true;
            } else {
                element.checked = false;
            }
        });
    }

    applySubject(subject) {
        document.querySelector("[name='subject']").value = subject;
    }

    applyTemplate(template) {
        document.querySelector("[name='template']").value = template;
        window.jQuery("[name='template']").selectpicker("val", template);
    }

    applyContent(content) {
        if (this.tinymce.activeEditor) {
            console.log(this.tinymce.activeEditor);
            this.tinymce.activeEditor.setContent(content);
        } else {
            console.warn("TinyMCE editor is not initialized.");
        }
    }

    applyScheduleSend(scheduleSend) {
        document.querySelector("[name='schedule-send']").value = scheduleSend;
    }

    applyTestEmail(testEmail) {
        document.getElementById("test-email").value = testEmail;
    }

    applyUsers(users) {
        document
            .querySelectorAll("#clients-table input[type='checkbox']")
            .forEach((checkbox) => {
                if (users.includes(checkbox.value)) {
                    checkbox.checked = true;
                } else {
                    checkbox.checked = false;
                }
            });
    }

    applyCollapsedCheckboxes(collapsedCheckboxes) {
        Object.keys(collapsedCheckboxes).forEach((key) => {
            document.getElementById(key).checked = collapsedCheckboxes[key];
            if (collapsedCheckboxes[key]) {
                document
                    .getElementById(key)
                    .dispatchEvent(new Event("click", { bubbles: true }));
            }
        });
    }
}
