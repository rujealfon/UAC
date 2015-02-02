pass=$(perl -e 'print crypt($ARGV[0], "password")', $3)

expect -c "
spawn ssh $5@$1
expect -re \".*?assword.*?\"
send \"$2\n\"
expect -re \".*?root.*?\"
send \"useradd -M -p $pass $4 -G $6\n\"
send \"echo DONE!!!\n\"
expect -re \"DONE!!!\""
