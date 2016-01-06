import unittest
from d27a1v2 import *

class Testadd_round_key(unittest.TestCase):
   
    def test_basic1(self):
        inputs = key_bv("3243f6a8885a308d313198a2e0370734")
        roundkey = [key_bv("2b7e1516"), key_bv("28aed2a6"),  key_bv("abf71588"), key_bv("09cf4f3c")]

        expected = "193de3bea0f4e22b9ac68d2ae9f84808"
       
        result = state_str(add_round_key(init_state_array(inputs), roundkey))
        
        self.assertTrue(result==expected)
    
    def test_basic2(self):
        inputs = key_bv("04e0482866cbf8068119d326e59a7a4c") 
        roundkey = [key_bv("a088232a"),key_bv("fa54a36c"),key_bv("fe2c3976"),key_bv("17b13905")] 
        result = state_str(add_round_key(init_state_array(inputs), roundkey))
        
        expected = "a4686b029c9f5b6a7f35ea50f22b4349"  

        self.assertTrue(result == expected)
    
    def test_basic3(self):
        inputs = key_bv("4b2c3337864a9dd28d89f4186d80e8d8") 
        roundkey = [key_bv("6d11dbca"),key_bv("880bf900"),key_bv("a33e8693"),key_bv("7afd41fd")] 
        result = state_str(add_round_key(init_state_array(inputs), roundkey))
        
        expected = "263de8fd0e4164d22eb7728b177da925"  

        self.assertTrue(result == expected)    
        
class Testsub_key_bytes(unittest.TestCase):
    def test_zerotest(self):
        inputs = key_bv("00000095")
        self.assertTrue(bv_hex_str(sub_key_bytes(inputs)) =="6363632a")
    
    def test_basic(self):
        inputs = key_bv("2b7e1516")
        self.assertTrue(bv_hex_str(sub_key_bytes(inputs)) == "f1f35947")    

class Testsbox_lookup(unittest.TestCase):
    def test_zerotest(self):
        inputs = key_bv("00")
        self.assertTrue(bv_hex_str(sbox_lookup(inputs))=="63")
    
    def test_basic(self):
        inputs = key_bv("15")
        self.assertTrue(bv_hex_str(sbox_lookup(inputs))=="59")
    def testsbox_lookup_last_element(self):
        input = BitVector.BitVector(bitstring = '11111111')
        answer = BitVector.BitVector(intVal = 22, size = 8)
        self.assertEqual(sbox_lookup(input), answer)
        
    def testsbox_lookup_any_element(self):
        input = BitVector.BitVector(bitstring = '11110000')
        answer = BitVector.BitVector(intVal = 140, size = 8)
        self.assertEqual(sbox_lookup(input), answer)
    def testsbox_lookup_first_element(self):
        input = BitVector.BitVector(bitstring = '00000000')
        answer = BitVector.BitVector(intVal = 99, size = 8)
        self.assertEqual(sbox_lookup(input), answer)
        
class Testsinit_key_schedule(unittest.TestCase):
    def test_basic(self):
        inputs = key_bv("2b7e151628aed2a6abf7158809cf4f3c")
        result = init_key_schedule(inputs)
        expected =  ['2b7e1516', '28aed2a6', 'abf71588', '09cf4f3c', 'a0fafe17', '88542cb1', '23a33939', '2a6c7605', 'f2c295f2', '7a96b943', '5935807a', '7359f67f', '3d80477d', '4716fe3e', '1e237e44', '6d7a883b', 'ef44a541', 'a8525b7f', 'b671253b', 'db0bad00', 'd4d1c6f8', '7c839d87', 'caf2b8bc', '11f915bc', '6d88a37a', '110b3efd', 'dbf98641', 'ca0093fd', '4e54f70e', '5f5fc9f3', '84a64fb2', '4ea6dc4f', 'ead27321', 'b58dbad2', '312bf560', '7f8d292f', 'ac7766f3', '19fadc21', '28d12941', '575c006e', 'd014f9a8', 'c9ee2589', 'e13f0cc8', 'b6630ca6']
        for i in range(len(expected)):
            expected[i] = key_bv(expected[i])
        self.assertTrue(result == expected)

class Testinv_sbox_lookup(unittest.TestCase):
    def testinv_sbox_lookup_first_element(self):
        input = BitVector.BitVector(bitstring = '00000000')
        answer = BitVector.BitVector(intVal = 82, size = 8)
        self.assertEqual(inv_sbox_lookup(input), answer)
        
    def testinv_sbox_lookup_last_element(self):
        input = BitVector.BitVector(bitstring = '11111111')
        answer = BitVector.BitVector(intVal = 125, size = 8)
        self.assertEqual(inv_sbox_lookup(input), answer)
        
    def testinv_sbox_lookup_any_element(self):
        input = BitVector.BitVector(bitstring = '11110000')
        answer = BitVector.BitVector(intVal = 23, size = 8)
        self.assertEqual(inv_sbox_lookup(input), answer)
        
class Testsub_bytes(unittest.TestCase):
    def test_basic1(self):
        inputs = [[key_bv("32"), key_bv("88"), key_bv("31"), key_bv("e0")],
                     [key_bv("43"), key_bv("5a"), key_bv("31"), key_bv("37")],
                     [key_bv("f6"), key_bv("30"), key_bv("98"), key_bv("07")],
                     [key_bv("a8"), key_bv("8d"), key_bv("a2"), key_bv("34")]]  
        result = sub_bytes(inputs)
        expected =  [[key_bv("23"), key_bv("c4"), key_bv("c7"), key_bv("e1")],
                     [key_bv("1a"), key_bv("be"), key_bv("c7"), key_bv("9a")],
                     [key_bv("42"), key_bv("04"), key_bv("46"), key_bv("c5")],
                     [key_bv("c2"), key_bv("5d"), key_bv("3a"), key_bv("18")]]
        self.assertTrue(result == expected)
    
    def testsub_bytes_first_element(self): 
        self.sa = init_state_array(BitVector.BitVector(size=128))
        answer = []
        for i in range(4):
            col = []
            for j in range (4):
                bit = BitVector.BitVector(intVal=99,size=8)
                col.append(bit)
            answer.append(col)
        self.assertEqual(sub_bytes(self.sa), answer)
    def testsub_bytes_last_element(self):
        self.sa = init_state_array(BitVector.BitVector(size=128).reset(1))
        answer=[]
        for i in range(4):
            col = []
            for j in range (4):
                bit = BitVector.BitVector(intVal=22,size=8)
                col.append(bit)
            answer.append(col)
        self.assertEqual(sub_bytes(self.sa), answer) 
    def test_basic2(self):
        inputs = key_bv("19a09ae93df4c6f8e3e28d48be2b2a08") 
        result = state_str(sub_bytes(init_state_array(inputs)))
        expected = "d4e0b81e27bfb44111985d52aef1e530"  
        self.assertTrue(result == expected)
    
    def test_basic3(self):
        inputs = key_bv("a4686b029c9f5b6a7f35ea50f22b4349") 
        result = state_str(sub_bytes(init_state_array(inputs)))
        expected = "49457f77dedb3902d296875389f11a3b"        
        self.assertTrue(result == expected)
class Testinv_sub_bytes(unittest.TestCase):
    def testinv_sub_bytes_first_element(self): 
        self.sa = init_state_array(BitVector.BitVector(size=128))
        answer = []
        for i in range(4):
            col = []
            for j in range (4):
                bit = BitVector.BitVector(intVal=82,size=8)
                col.append(bit)
            answer.append(col)
        self.assertEqual(inv_sub_bytes(self.sa), answer)
    def testinv_sub_bytes_last_element(self):
        self.sa = init_state_array(BitVector.BitVector(size=128).reset(1))
        answer=[]
        for i in range(4):
            col = []
            for j in range (4):
                bit = BitVector.BitVector(intVal=125,size=8)
                col.append(bit)
            answer.append(col)
        self.assertEqual(inv_sub_bytes(self.sa), answer)
        
    def testinv_sub_bytes_gen1(self):
        inputs = key_bv("19a09ae93df4c6f8e3e28d48be2b2a08") 
        result = state_str(inv_sub_bytes(init_state_array(inputs)))
        answer = "8e4737eb8bbac7e14d3bb4d45a0b95bf"  
        self.assertTrue(result == answer)
    def testinv_sub_bytes_gen2(self):
        inputs = key_bv("a4686b029c9f5b6a7f35ea50f22b4349") 
        result = state_str(inv_sub_bytes(init_state_array(inputs)))
        answer = "1df7056a1c6e57586bd9bb6c040b64a4"  
        self.assertTrue(result == answer)

class Testshift_bytes_left(unittest.TestCase):
    def testshift_bytes_left_0(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='00001011010010111101111000110000')
        self.assertEqual(shift_bytes_left(self.bv, 0), answer)
        
    def testshift_bytes_left_2(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='11011110001100000000101101001011')
        self.assertEqual(shift_bytes_left(self.bv, 2), answer)
        
    def testshift_bytes_left_4(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='00001011010010111101111000110000')
        self.assertEqual(shift_bytes_left(self.bv, 4), answer)
        
class Testshift_bytes_right(unittest.TestCase):
    def testshift_bytes_right_0(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='00001011010010111101111000110000')
        self.assertEqual(shift_bytes_right(self.bv, 0), answer)
        
    def testshift_bytes_right_2(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='11011110001100000000101101001011')
        self.assertEqual(shift_bytes_right(self.bv, 2), answer)
        
    def testshift_bytes_right_4(self):
        self.bv = BitVector.BitVector(size=32, intVal=189521456)
        answer = BitVector.BitVector(bitstring='00001011010010111101111000110000')
        self.assertEqual(shift_bytes_right(self.bv, 4), answer)
class Testshift_rows(unittest.TestCase):
    def testshift_rows_gen1(self):
        inputs = key_bv("d42711aee0bf98f1b8b45de51e415230") 
        result = state_str(shift_rows(init_state_array(inputs)))
        answer = "d4bf5d30e0b452aeb84111f11e2798e5"  
        self.assertTrue(result == answer)        
    def testshift_rows_gen2(self):
        inputs = key_bv("49ded28945db96f17f39871a7702533b") 
        result = state_str(shift_rows(init_state_array(inputs)))
        answer = "49db873b453953897f02d2f177de961a" 
        self.assertTrue(result == answer)
    def testshift_rows_gen3(self):
        inputs = key_bv("ac73cf7befc111df13b5d6b545235ab8") 
        result = state_str(shift_rows(init_state_array(inputs)))
        answer = "acc1d6b8efb55a7b1323cfdf457311b5"        
        self.assertTrue(result == answer)
        

class Testinv_shift_rows(unittest.TestCase):
    def testinvshift_rows_gen1(self):
        inputs = key_bv("d42711aee0bf98f1b8b45de51e415230") 
        result = state_str(inv_shift_rows(init_state_array(inputs)))
        answer = "d4415df1e02752e5b8bf11301eb498ae"  
        self.assertTrue(result == answer)        
    def testinvshift_rows_gen2(self):
        inputs = key_bv("49ded28945db96f17f39871a7702533b") 
        result = state_str(inv_shift_rows(init_state_array(inputs)))
        answer = "490287f145de531a7fdbd23b77399689" 
        self.assertTrue(result == answer)
    def testinvshift_rows_gen3(self):
        inputs = key_bv("ac73cf7befc111df13b5d6b545235ab8") 
        result = state_str(inv_shift_rows(init_state_array(inputs)))
        answer = "ac23d6dfef735ab513c1cfb845b5117b"        
        self.assertTrue(result == answer)
        
class Testmix_columns(unittest.TestCase):
    def test_basic1(self):
        inputs = init_state_array(key_bv("d4bf5d30e0b452aeb84111f11e2798e5"))
        result = state_str(mix_columns(inputs))
        expected = "046681e5e0cb199a48f8d37a2806264c"

        self.assertTrue(result == expected)
        
    def test_basic2(self):
        inputs = init_state_array(key_bv("49db873b453953897f02d2f177de961a"))
        result = state_str(mix_columns(inputs))
        expected = "584dcaf11b4b5aacdbe7caa81b6bb0e5"
        self.assertTrue(result == expected)
    
    def test_basic3(self):
        inputs = init_state_array(key_bv("acc1d6b8efb55a7b1323cfdf457311b5"))
        result = state_str(mix_columns(inputs))
        expected = "75ec0993200b633353c0cf7cbb25d0dc"
        self.assertTrue(result == expected)

class Testinv_mix_columns(unittest.TestCase):
    def test_basic1(self):
        inputs = init_state_array(key_bv("046681e5e0cb199a48f8d37a2806264c"))
        result = state_str(inv_mix_columns(inputs))
        expected = "d4bf5d30e0b452aeb84111f11e2798e5"

        self.assertTrue(result == expected)
        
    def test_basic2(self):
        inputs = init_state_array(key_bv("584dcaf11b4b5aacdbe7caa81b6bb0e5"))
        result = state_str(inv_mix_columns(inputs))
        expected = "49db873b453953897f02d2f177de961a"
        self.assertTrue(result == expected)
    
    def test_basic3(self):
        inputs = init_state_array(key_bv("75ec0993200b633353c0cf7cbb25d0dc"))
        result = state_str(inv_mix_columns(inputs))
        expected = "acc1d6b8efb55a7b1323cfdf457311b5"
        self.assertTrue(result == expected)            
class Testencrypt(unittest.TestCase):
    def teset_encrypt_gen1(self):
        plain_text = "00112233445566778899aabbccddeeff"
        key = "000102030405060708090a0b0c0d0e0f"
        answer = "69c4e0d86a7b0430d8cdb78070b4c55"
        result = encrypt(key,plain_text)
        self.assertTrue(result == answer)
    def test_encrypt_gen2(self):
        plain_text = "3243f6a8885a308d313198a2e0370734"
        key = "2b7e151628aed2a6abf7158809cf4f3c"
        answer = "3925841d02dc09fbdc118597196a0b32"
        result = encrypt(key,plain_text)
        self.assertTrue(result == answer)
class Testdecrypt(unittest.TestCase):
    def test_decrypt_gen1(self):
        cipher_text = "69c4e0d86a7b0430d8cdb78070b4c55a"
        key = "000102030405060708090a0b0c0d0e0f"
        answer = "00112233445566778899aabbccddeeff"
        result = decrypt(key,cipher_text)
        self.assertTrue(result == answer)

    def test_decrypt_gen2(self):
        cipher_text = "3925841d02dc09fbdc118597196a0b32"
        key = "2b7e151628aed2a6abf7158809cf4f3c"
        answer = "3243f6a8885a308d313198a2e0370734"
        result = decrypt(key,cipher_text)
        self.assertTrue(result == answer)
    
if __name__ == '__main__':
    unittest.main(exit=False)
