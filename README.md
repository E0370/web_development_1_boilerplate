## Instructions

Please keep using `port 90`.

# DataBase phpMyAdmin

- Username: root
- Password: secret123

## Credentials

### Admin

Right now, there is only one user with the admin role, but the admin can change the role of any user.

- Username: ehtisham3
- Password: Ehtisham03@

### User

- Username: kamran4
- Password: Tabl@Chair03
- If you create a new user, the role in the database is set to normal user. Only the admin can update the role.

## Flow

- Any visitor who visits the website can see all the posts.
- In order to claim the item on a post, the user has to click claim, but once the user clicks the claim button, he will be redirected to the login page.
- On the login page, the user can log in with the username and password. If the user does not have an account, he can create a new account by clicking on create an account.
- Once the user has logged in, he can create a post, view all his posts, and view his message inbox.
- On the other hand, the admin will have an extra button, 'Admin'.
- Admin can see all the existing users, can change the role of any user, and can also delete any user.
- Admin can also see all the existing posts and can edit and delete them as well.

## PS

- One of my database table names is items. The actual item is referred to as Posts, meaning what the user will post. As it was a bit late, I was not able to change the database table name. And in class, you mentioned that our repo and controller names should be the same as our database table name. But in the view, you will see 'Create Post' and 'My Posts'. So you do not get confused with the naming, I thought I should add it here in the README.

## Important Functionalities

- If a user has an account but forgot the password, the user can click on forgot password and will be redirected to the forgot password page, where the user has to enter the email. If the email exists in the database, the user will get the forgot password link, which will expire after 1 hour.
- //Email code
- App/Services/MailService.php
- When the user is logged in, he can chat with another user. If the user sees a post and clicks on the claim button, he will be redirected to the chat box.
- !Important: User cannot send a message to his own post.

## Efforts for WCAG

- The website is accessible to assistive technologies (e.g., using semantic HTML tags like header, main, nav, footer, section, and article; ARIA properties like aria-label and role attributes; label tags for form inputs; and alt text for images).
- Sufficient color contrast has been ensured across the website.

## Efforts for GDPR

- The website lets the user know what we are doing with their personal data and how we are protecting it.
- A separate privacy statement page has been created (see footer).
- I ensured passwords are stored securely using hashing.
- Overall, I have fully studied WCAG and GDPR. During this term, while designing the Haarlem Festival website, we had to do research on WCAG rules and had to apply them in our design as well.
