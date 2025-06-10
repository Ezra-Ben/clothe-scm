                                      Team Setup Guide: Laravel + Livewire + Volt Project

Welcome, team! This guide will help you set up the Laravel + Livewire + Volt project on your local machine for collaboration and development.


   Git Workflow for Collaboration:

Step-by-step Git Flow (with explanation):

1. **Ensure you're on the main branch**

bash:
git checkout main
git pull origin main
====
Sync with the latest project code.


2. **Create a new branch for your task**

bash:
git checkout -b feature/your-task-name
====
Keeps your work isolated and trackable.


3. **Do your coding work**
   Create your files in the proper Laravel folders (models, migrations, components, etc.).


4. **Stage your changes**

bash:
git add .
====
Prepares all your changes for commit.


5. **Commit your changes**

bash
git commit -m "Describe your work briefly"
====
Saves a snapshot of your work with a message.


6. **Push your branch to GitHub**

bash:
git push origin feature/your-task-name
Sends your branch and work to the remote repository.


7. **Create a Pull Request (PR)**
   Go to GitHub → Click "Compare & pull request" → Fill details → Submit.

8. **I will perform the reviews and merge to `main`**
   Your work becomes part of the main project code.

---

##  Task Division by Team Member

11th-13th June-2025
### Main Feature: Vendor Registration + Validation:

How the flow works:
- Vendor submits registration form → VendorRegisterForm Livewire component handles UI.
- Form data is sent to Laravel backend → Route in routes/web.php processes the request.
- Laravel calls VendorService → Handles validation logic.
- VendorService makes an API request to Java server → Sends vendor data for validation.
- Java server processes validation → Returns response.
- Laravel receives validation result → Stores vendor data or returns errors.
 

| Member         | Task                                          | Layer(s)           | Directory/File Location                         | Language(s)          |? |
| -------------- | --------------------------------------------- | ------------------ | ----------------------------------------------- | -------------------- |--|
| Kristiana      | Create `vendors` table migration + model      | Data               | `app/Models`, `database/migrations`             | PHP                  |  |
| Dilis          | Build `VendorRegisterForm` Livewire component | Presentation       | `app/Livewire`, `resources/views/livewire`      | PHP + Blade          |  |
| Patrick        | Connect route + integrate form with system    | Application        | `routes/web.php`                                | PHP                  |  |
| Sarah          | Build `VendorService` for logic + validation  | Business Logic     | `app/Services/VendorService.php`                | PHP(calls Java API)  |  |         
| Ezra           | Handle Java server integration for validation | Business Logic/API | `separate port`                                 | Java                 |  |

---

## Need Help or Clarification?
Feel free to reach out to anyone.

Happy coding, team! 