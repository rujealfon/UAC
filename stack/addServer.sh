expect -c "
spawn ssh $1@$2
expect -re \".*?assword.*?\"
send \"$3\n\"
expect -re \".*?root.*?\"
send \"groupadd developer\n\"
send \"chgrp -R developer /server\n\"
send \"chmod -R 775 /server\n\"
send \"echo DONE!!!\n\"
expect -re \"DONE!!!\""