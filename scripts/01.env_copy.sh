#!/bin/bash

# Copy the environment file to the current directory
cp ../src/.env.example ../src/.env

if [ $? -eq 0 ]; then
  echo ".envファイルのコピーが完了しました。環境変数の変更を忘れずに行ってください"
else
  echo "Environment file copy failed"
fi