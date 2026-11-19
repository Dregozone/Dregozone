# cloud-worker-setup

A Laravel project. Run the tests with `php artisan test --compact`.

<!-- cloud-worker:start (kept by Life OS: docs/office/cloud-worker/CLAUDE-section.md; edit it there) -->
# Working on an issue in the cloud

These rules apply when you're in a Claude Code **cloud session** (`CLAUDE_CODE_REMOTE` is `true`),
started from Life OS's "☁ Cloud worker" to work on a GitHub issue. The person reads your work on
their phone and may be away, so be thorough and don't stop for things you can decide yourself.

1. **Read the issue first.** Use your GitHub tools to read the issue named in the first message,
   with all its comments. If you can't read it, stop and say so: never guess from the title.
2. **Make a branch** from the default branch, named `issue-<number>-<short-title>` (for example
   `issue-17-birthday-reminders`). If pushing that name is refused, use the branch the session
   gives you; the pull request still links the issue.
3. **Do the work** the way this repository does things (the rest of this file, and its docs).
   The session's startup hook installed the dependencies: read its output, and if a step failed
   (for example Composer couldn't reach a private package), say so in the pull request.
4. **Run the tests** (`php artisan test --compact`, plus `npm run build` if you changed the front
   end), and fix what you broke. Use SQLite for tests: there's no MySQL here.
5. **Bring the branch up to date** before the pull request: fetch, rebase onto the default branch,
   and run the tests again.
6. **Open the pull request yourself**, ready for review (not a draft):
   - Use your GitHub tools, or `gh api` (REST). `gh pr create` and `gh issue` don't work here
     (GitHub's GraphQL is blocked). For example:
     `gh api repos/OWNER/REPO/pulls -f title="…" -f head="issue-17-…" -f base="main" -f body="…"`
   - The description says what changed and why. Include an **Assumptions** section listing any
     choice you made without asking. End the description with `Closes #<number>`.
   - Where it helps the review (a visible change), add screenshots: commit them under
     `.github/pr-images/` on the branch and embed each with
     `![what it shows](https://github.com/OWNER/REPO/blob/COMMIT_SHA/.github/pr-images/NAME.png?raw=true)`.
7. **Check for overlap:** list the other open pull requests' files (`gh api repos/OWNER/REPO/pulls`
   and `…/pulls/N/files`). If any touch the same files, add a line to your description such as
   "Overlaps #12 in routes/web.php: either can merge first". Never push to another pull request's
   branch.
8. **Questions:** only ask when a real decision is needed that you can't sensibly make (the person
   answers from a phone). Otherwise choose, and list it under Assumptions.
9. **Never** print credentials, tokens or environment variables, and never commit `.env` or
   `auth.json`.

Outside a cloud session (at a desk on the PC), Life OS gives its own instructions instead: follow
those.
<!-- cloud-worker:end -->
