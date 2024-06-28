import express from 'express';
import { getAllUsuarios, getUsuarioByCorreo } from '../controllers/UserController.js';

const router = express.Router();

router.get('/usuarios', getAllUsuarios);
router.get('/usuarios/:correo', getUsuarioByCorreo);


export default router;
