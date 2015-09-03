jQuery(document).ready ->

  greetings = '$ pwd\nSchool of Software Engineering\n\n'
  greetings += '$ cd ' + error_url + '\n'

  if jQuery('body').hasClass('page-404')
    greetings += 'cd: no such file or directory\n'
  else
    greetings += 'cd: Permission denied\n'

  jQuery('#terminal').terminal (command, term) ->
    if command is 'pwd'
      term.echo 'School of Software Engineering\n'
      return

    term.echo 'command not found: ' + command + '\n'
    return
  ,
    prompt: '$ '
    greetings: greetings
    width: 500
    height: 300
    name: 'sse'
