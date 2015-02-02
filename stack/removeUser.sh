expect -c "
spawn ssh $1@$2
expect -re \".*?assword.*?\"
send \"$3\n\"
expect -re \".*?root.*?\"
send \"userdel $4\n\"
send \"echo DONE!!!\n\"
expect -re \"DONE!!!\""
