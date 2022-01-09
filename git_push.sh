#!/bin/zsh

# user入力値
# $1だとスペース区切りの最初の単語しか取得しないため、$@で全て取得
message="$@"
# 入力が無ければデフォルトでupdateとする
if [[ -z $message ]]; then
    message="update"
fi

# 現在のbranch名を取得
branch=$(git rev-parse --abbrev-ref HEAD)

# gitにpush
cd /Applications/MAMP/htdocs/my_output
git add .
# ""で$messageを囲まないと、複数単語入っている場合、1つのcommit messageと認識されずerrorになる
git commit -m "$message"
git push origin "$branch"