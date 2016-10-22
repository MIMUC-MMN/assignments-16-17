# assignments-16-17
Public repo for student-contributed solutions for the assignments (Online Multimedia @ LMU)

## How to contribute ##
We assume you have git installed on your machine. If not, download it here: https://git-scm.com/
 
### Step 1: Create a GitHub account ###
1. Create a GitHub account here: https://github.com/join
2. Create an SSH-key for your machine by executing `ssh-keygen` in either bash or Git-Bash. 
Follow the instructions. It's not a bad idea to protect the key with a password, but it's not
absolutely necessary if you're on a private computer.
3. Copy the SSH-key that you find in `~/.ssh/id_rsa.pub`
4. Enter the SSH-key on GitHub, which will allow you to push code to repos for which you have access.
This is the URL to do that: https://github.com/settings/keys  


### Step 2: Workspace Set-up
1. Make a project directory like so `mkdir ~/mmn && cd ~/mmn`
2. Clone this repository to your own machine. `git clone git@github.com:MIMUC-MMN/assignments-16-17.git`
3. Fork the repository: https://github.com/MIMUC-MMN/assignments-16-17#fork-destination-box (click the "Fork" Button)
4. Add the repository as remote origin: `git remote add myfork <YOUR_REPOSITORY_URL>`
5. Set the upstream branch to your own repo: `git branch -u myfork/master`
6. Push your code and when you're happy with it, create a Pull Request (PR) on GitHub.

### Notes ###
- We will merge your repos as they are, but sometime we make small modifications or comments
- Make sure you keep your repository in sync with the origin like so `git pull origin master && git merge origin/master`.
Resolve any conflicts on your machine before pushing.
- It's probably best if you somehow prefix your folder names with your initials / name / alias. Like so: `darkwingDuck_task01`, `darkwingDuck_task02`... you get the idea  

## Help! ##
If you're stuck, don't worry. We'll help you either before/after the tutorials or on Slack: 
https://mimuc.slack.com/messages/mmn-ws1617 
